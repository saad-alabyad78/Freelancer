<?php

namespace App\Http\Controllers\Company;

use App\Models\Image;
use App\Models\Company;
use App\Models\ContactLink;
use App\Models\CompanyPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Requests\Company\CreateCompanyRequest;
use App\Http\Requests\Company\DeleteCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Requests\Company\CreateCompanyImageRequest;

/**
 * @group Company Managment
 * 
 */
class CompanyController extends Controller
{
    /**
     * Display a pagination of companies .
     */
    public function index()
    {
        //?????????????????????????????
    }

    /**
     * Store New Company .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Company\CompanyResource
     * @apiResourceModel App\Models\Company with=App\Models\ContactLink,App\Models\GalleryImage,App\Models\CompanyPhone
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function store(CreateCompanyRequest $request)
    {
        DB::beginTransaction();
        
        $data = $request->validated();

        $data['username'] = auth()->user()->slug ;

        try {
            //create company
            $company = Company::create($data);

            $company->user()->save(auth()->user()) ;

            //create links 

            if(array_key_exists('contact_links' , $data))
            {
                $contact_links = [] ;
            
                foreach($data['contact_links'] as $link_name)
                {
                    $contact_links[] = new ContactLink(['name' => $link_name]) ;
                }
                $company->contact_links()->saveMany($contact_links) ;
            }

            //store and create images

            if(array_key_exists('gallery_images_ids' , $data))
            {
                //todo:chnuk set in the database                 

                $gallery_images = Image::whereIn('id' , $data['gallery_images_ids'])->get() ;
                
                $company->gallery_images()->saveMany($gallery_images) ;
            }

            //create phones 

            if(array_key_exists('company_phones' , $data))
            {
                $company_phones = [] ;
            
                foreach($data['company_phones'] as $company_phone)
                {
                    $company_phones[] = new CompanyPhone(['number' => $company_phone]) ;
                }
                $company->company_phones()->saveMany($company_phones) ;

            }

            DB::commit() ;
        
            return CompanyResource::make($company->load([
                'contact_links'  ,
                'gallery_images' ,
                'company_phones' ,
                ]))->response()->setStatusCode(201) ;

        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'message' => 'something went wrong' ,
                'error' => $th->getMessage() 
                ] , 400) ;
        }
    }

    /**
     * Show Company .
     * 
     * 
     * @apiResource App\Http\Resources\Company\CompanyResource
     * @apiResourceModel App\Models\Company with=App\Models\ContactLink,App\Models\GalleryImage,App\Models\CompanyPhone
     * 
     * 
     * @return \Illuminate\Http\JsonResponse 
     * 
     */
    public function show(Company $company)
    {
        return CompanyResource::make($company->load([
            'contact_links'  ,
            'gallery_images' ,
            'company_phones' ,
            ]))->response()->setStatusCode(200) ; ;
    }
    /**
     * Update Company .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Company\CompanyResource
     * @apiResourceModel App\Models\Company with=App\Models\ContactLink,App\Models\GalleryImage,App\Models\CompanyPhone
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function update(UpdateCompanyRequest $request)
    {
        DB::beginTransaction() ;
        
        $data = $request->validated() ;

        

        try {
            $company = Company::findOrFail(auth()->user()->role['id']);

            //todo : delete the old images profile and background in the observer

            $company->update($data) ;

            //update gallery images
            if(array_key_exists('gallery_images' , $data))
            {
                $idsArray = array_map( function($item){
                    return $item['id'];
                }, $data['gallery_images']) ;

                $company->gallery_images()
                ->whereNotIn('id' , $idsArray)->update([
                    'imagable_id' => null ,
                    'imagable_type' => null ,
                    'deleted' => true 
                ]) ;
    
                Image::whereIn('id' , $idsArray)
                     ->whereNull('imagable_id')
                     ->whereNull('imagable_type')
                     ->update([
                        'imagable_id' => $company->id ,
                        'imagable_type' => Company::class ,
                     ]) ;
            }
            
            
            //update contact links
            if(array_key_exists('contact_links' , $data))
            {
                $company->contact_links()->whereNotIn('name' , $data['contact_links'])->delete() ;
                $names = $company->contact_links()->pluck('name')->toArray() ;
                $contact_links = [] ;
            
                foreach($data['contact_links'] as $link_name)
                {
                    if(!in_array($link_name , $names))
                    $contact_links[] = new ContactLink(['name' => $link_name]) ;
                }
                $company->contact_links()->saveMany($contact_links) ;
            }

            //update company phones 
            if(array_key_exists('company_phones' , $data))
            {
                $company->company_phones()->whereNotIn('number' , $data['company_phones'])->delete() ;
                $numbers = $company->company_phones()->pluck('number')->toArray() ;
                $company_phones = [] ;
            
                foreach($data['company_phones'] as $company_phone)
                {
                    if(!in_array($company_phone , $numbers))
                    $company_phones[] = new CompanyPhone(['number' => $company_phone]) ;
                }
                $company->company_phones()->saveMany($company_phones) ;
            }
            DB::commit() ;
            
            return CompanyResource::make($company->load([
                'contact_links'  ,
                'gallery_images' ,
                'company_phones' ,
                ]))->response()->setStatusCode(200) ;
                
        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'message' => $th->getMessage() ,
                'trace' => $th->getTrace() ,
            ], 400) ;
        }
    }

    /**
     * Delete the company.
     * Note: the user will be deleted 
     * 
     * @authenticated
     * 
     * return 422 if password is incurrect
     * 
     *  @return \Illuminate\Http\Response | \Illuminate\Routing\ResponseFactory
     */
    public function delete(DeleteCompanyRequest $request)
    {
        $password = $request->validated()['password'] ;

        if(!Hash::check($password , auth()->user()->getAuthPassword())){
            return response()->json([
                'error' => 'wrong password'
            ] , 422 );
        }

        $company = Company::findOrFail(auth()->user()->role_id) ;

        $company->delete() ;

        return response()->noContent() ;
    }
}