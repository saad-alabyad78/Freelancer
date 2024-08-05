<?php

namespace App\Http\Controllers\Product;

use App\Models\File;
use App\Models\User;
use App\Models\Image;
use App\Models\Client;
use App\Models\Product;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
/**
 *@group Products
 **/
class ProductController extends Controller
{
    /**
     * List 
     * 
     * list of products 
     * 
     * @unauthenticated
     * 
     * send ?random=1 to get resault in random order 
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $products = Product::query() ;

        if(request()->boolean('random'))
        {
            $products->inRandomOrder() ;
        }

        return ProductResource::collection($products->paginate(20)) ;
    }
    /**
     * Buy Product
     * 
     * @param \App\Models\Product $product
     * @return ProductResource
     */
    public function buyProduct(Product $product)
    {
        $user = User::where('id' , auth('sanctum')->id())->first() ;
        
        if($user->money < $product->price){
            return response()->json(['message'=>'poor']) ;
        }
        
        $freelancer = Freelancer::where('id' , $product->freelancer_id)->first() ;
        $userFreelancer = User::where('role_id' , $freelancer->id)
            ->where('role_type' , Freelancer::class)
            ->first() ;
        
        $user->decrement('money' , $product->price) ;
        $userFreelancer->increment('money' , $product->price) ;

        $product->clients()->attach($user->role_id) ;

        return ProductResource::make($product->load(['files' , 'image' , 'images'])) ;
    }
    /**
     * My products
     * 
     * for freelancer or for client
     * 
     * @return mixed|\Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function myProducts()
    {
        $user = User::where('id' , auth('sanctum')->id())->first() ;
        
        if($user == null){
            return response()->json([
                'message' => 'unauthenticated' ,
            ] , 401) ;
        }
        
        $products = null ;
        
        if($user->role_type == Freelancer::class)
        {
            $products = Product::where('freelancer_id' , $user->role_id) ;
        }
        else if($user->role_type == Client::class)
        {
            $products = Product::withTrashed();
            
            $products->whereHas('clients' , function($query)use(&$user){
                $query->where('client_id' , $user->role_id) ;
            }) ;
        }
        else{
            return response()->json([
                'message' => 'unauthorized' ,
            ] , 403) ;
        }
        return ProductResource::collection($products->paginate(20)) ;
    }

    /**
     * Show
     * 
     * @unauthenticated
     * 
     * @param \App\Models\Product $product
     * @return ProductResource
     */
    public function show(Product $product)
    {
        $user = User::where('id' , auth('sanctum')->id())->first() ;
        
        if($user and $user->role_type == Freelancer::class)
        {
            $product->where('freelancer_id' , $user->role_id) ;
            $product->load(['files' , 'image' , 'images']) ;
        }
        else if($user and $user->role_type == Client::class)
        {
            $mine = DB::table('client_product')
            ->where('product_id' , $product->id)
            ->where('client_id' , $user->role_id)
            ->exists() ;

            if($mine)
            {
                $product->load(['files' , 'image' , 'images']) ;
            }
        }else{
            $product->load(['image' , 'images']) ;
        }
        return ProductResource::make($product) ;
    }

    /**
     * Create or Store
     * 
     * @param \App\Http\Requests\Product\StoreProductRequest $request
     * @return mixed
     */
    public function store(StoreProductRequest $request)
    {
        try {
            return DB::transaction(function()use(&$request){
                
                $data = $request->validated() ;

                $user = User::where('id' , auth('sanctum')->id())->first() ;

                $data['freelancer_id'] = $user->role_id ;

                $product = Product::create($data) ;

                File::whereIn('id' ,  $data['file_ids'])
                ->update([
                    'filable_id' => $product->id ,
                    'filable_type' => Product::class ,
                ]) ;
                
                Image::whereIn('id' ,  $data['image_ids'])
                ->update([
                    'imagable_id' => $product->id ,
                    'imagable_type' => Product::class ,
                ]) ;

                $product->load(['files' , 'image' , 'images']) ;

                return ProductResource::make($product) ;
            }) ;
        } catch (\Throwable $th) {
            throw $th ;
        }
    }
    /**
     * Update
     * 
     * @param \App\Models\Product $product
     * @param \App\Http\Requests\Product\UpdateProductRequest $request
     * @return mixed
     */
    public function update(Product $product , UpdateProductRequest $request)
    {
        try {
            return DB::transaction(function()use(&$product , &$request){
                
                $data = $request->validated() ;

                $product->update($data) ;

                if($data['file_ids'] ?? false)
                {
                    File::where('filable_id' , $product->id)
                    ->where('filable_type' , Product::class )
                    ->update([
                        'filable_id' => null ,
                        'filable_type' => null ,
                    ]) ;

                    File::whereIn('id' ,  $data['file_ids'])
                    ->update([
                        'filable_id' => $product->id ,
                        'filable_type' => Product::class ,
                    ]) ;
                }
                
                
                if($data['image_ids'] ?? false)
                {
                    Image::where('imagable_id' , $product->id)
                    ->where('imagable_type' , Product::class )
                    ->update([
                        'imagable_id' => null ,
                        'imagable_type' => null ,
                    ]) ;

                    Image::whereIn('id' ,  $data['image_ids'])
                    ->update([
                        'imagable_id' => $product->id ,
                        'imagable_type' => Product::class ,
                    ]) ;
                }

                $product->load(['files' , 'image' , 'images']) ;

                return ProductResource::make($product) ;
            }) ;
        } catch (\Throwable $th) {
            throw $th ;
        }
    }

    /**
     * dalete
     * 
     * if the freelancer deleted 
     * it will still be present in client account
     * 
     * @param \App\Models\Product $product
     * @return ProductResource
     */
    public function delete(Product $product)
    {
        if(request()->boolean('force'))
        {
            $product->forceDelete() ;
            return response()->json(['deleted for ever']) ;
        }
        
        $product->deleteOrFail() ;
    
        return ProductResource::make($product) ;
    }
}
