<?php

namespace App\Http\Controllers\Freelancer;

use App\Models\File;
use App\Models\Image;
use App\Models\Portfolio;
use App\Models\Skillable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Freelancer\PortfolioResource;
use App\Http\Requests\Freelancer\CreatePortfolioRequest;
use App\Http\Requests\Freelancer\DeletePortfolioRequest;
use App\Http\Requests\Freelancer\UpdatePortfolioRequest;
/**
 *@group Freelancer Managment 
 *
 **/
class PortfolioController extends Controller
{
     /**
     * Get Portfolio .
     * 
     * 
     * @apiResource App\Http\Resources\Freelancer\PortfolioResource with=App\Http\Resources\Category\SkillResource
     * @apiResourceModel App\Models\Portfolio with=App\Models\Skill,App\Models\File,App\Models\Image
     * 
     * 
     * @return \App\Http\Resources\Freelancer\PortfolioResource
     * 
     */
    public function show(Portfolio $portfolio)
    {
        return PortfolioResource::make($portfolio->load(['skills' , 'files' , 'images'])) ;
    }    
    /**
     * Get Portfolio .
     * 
     * 
     * @apiResource App\Http\Resources\Freelancer\PortfolioResource with=App\Http\Resources\Category\SkillResource
     * @apiResourceModel App\Models\Portfolio with=App\Models\Skill,App\Models\File,App\Models\Image
     * 
     * 
     * @return \App\Http\Resources\Freelancer\PortfolioResource
     * 
     */
    public function store(CreatePortfolioRequest $request)
    {
        DB::beginTransaction();
        
        $data = $request->validated();
        $data['freelancer_id'] = auth()->user()->role['id'] ;

        try {

            $portfolio = Portfolio::create($data);
            
            $skillables = array_map(function($item) use ($portfolio){
                return [
                    'skill_id' => $item ,
                    'skillable_id' => $portfolio->id ,
                    'skillable_type' => Portfolio::class ,
                ] ;
            } , $data['skill_ids']);
            
            Skillable::insert($skillables) ;

            if(array_key_exists('file_ids' , $data))
            {
                $idsArray = array_map( function($item){
                    return $item['id'];
                }, $data['file_ids']) ;
    
                File::whereIn('id' , $idsArray)
                     ->whereNull('filable_id')
                     ->whereNull('filable_type')
                     ->update([
                        'filable_id' => $portfolio->id ,
                        'filable_type' => Portfolio::class ,
                     ]) ;
            }
            
            if(array_key_exists('image_ids' , $data))
            {
                $idsArray = array_map( function($item){
                    return $item['id'];
                }, $data['image_ids']) ;

                Image::whereIn('id' , $idsArray)
                     ->whereNull('imagable_id')
                     ->whereNull('imagable_type')
                     ->update([
                        'imagable_id' => $portfolio->id ,
                        'imagable_type' => Portfolio::class ,
                     ]) ;
            }

            DB::commit() ;
 

            return PortfolioResource::make($portfolio->load(['skills' , 'files' , 'images']));

        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'err' => $th ,
                'message' => 'something went wrong' ,
                'error' => $th->getMessage() ,
                'line' => $th->getLine()
                ] , 400) ;
        }
    }
    /**
     * Get Portfolio .
     * 
     * 
     * @apiResource App\Http\Resources\Freelancer\PortfolioResource with=App\Http\Resources\Category\SkillResource
     * @apiResourceModel App\Models\Portfolio with=App\Models\Skill,App\Models\File,App\Models\Image
     * 
     * 
     * @return \App\Http\Resources\Freelancer\PortfolioResource
     * 
     */
    public function update(UpdatePortfolioRequest $request)
    {
        $portfolio = Portfolio::where([
            'id' => $request->portfolio_id ,
            'freelancer_id' => auth()->user()->role['id'] 
        ])->first() ;

        if($portfolio == null)
        {
            return response()->json([
                'message' => 'you do not have a portfolio with id = ' . $request->portfolio_id
            ] , 404);
        }

        $data = $request->validated() ;

        $portfolio->update($data) ;

        if(array_key_exists('skill_ids' , $data))
        {
            Skillable::where('skillable_id' , $portfolio->id)
                ->whereNotIn('skill_id' , $data['skill_ids'])
                ->delete() ;
                
            $skillables = array_map(function($id) use ($portfolio){
                return [
                    'skill_id' => $id , 
                    'skillable_id' => $portfolio->id ,
                    'skillable_type' => Portfolio::class ,
                ];
            } , $data['skill_ids']) ;

            Skillable::upsert($skillables , 'id') ;
                        
        }
        if(array_key_exists('file_ids' , $data))
        {
            File::where('filable_id' , $portfolio->id)
                ->whereNotIn('id' , $data['file_ids'])
                ->update([
                    'deleted' => true ,
                    'filable_id' => null ,
                    'fillable_type' => null ,
                    ]);

            File::where('filable_id' , $portfolio->id)
                ->whereIn('id' , $data['file_ids'])
                ->update([
                    'filable_id' => $portfolio->id ,
                    'filable_type' => Portfolio::class ,
                    ]);
        }
        if(array_key_exists('image_ids' , $data))
        {
            Image::where('imagable_id' , $portfolio->id)
                ->whereNotIn('id' , $data['image_ids'])
                ->update([
                    'deleted' => true ,
                    'imagable_id' => null ,
                    'imaglable_type' => null ,
                    ]);
                    
            Image::where('imagable_id' , $portfolio->id)
                ->whereIn('id' , $data['file_ids'])
                ->update([
                    'imagable_id' => $portfolio->id ,
                    'imagable_type' => Portfolio::class ,
                    ]);
        }

        return PortfolioResource::make($portfolio->load(['files' , 'images' , 'skills']));
    }

    /**
     * Delete Portfolio.
     * 
     * @authenticated
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function delete(DeletePortfolioRequest $request)
    {
        $portfolio = Portfolio::where([
            'id' => $request->portfolio_id ,
            'freelancer_id' => auth()->user()->role['id'] 
        ])->first() ;

        if($portfolio == null)
        {
            return response()->json([
                'message' => 'you do not have a portfolio with id = ' . $request->portfolio_id
            ] , 404);
        }

        $portfolio->delete() ;

        return response()->json(['message'=>'deleted']) ;
    }
}
