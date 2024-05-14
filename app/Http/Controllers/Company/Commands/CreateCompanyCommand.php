<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Constants\Disks;
use App\Models\Industry;
use App\Models\ContactLink;
use App\Models\CompanyPhone;
use App\Models\GalleryImage;
use App\Services\imageService;
use App\Constants\CloudFolders;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Requests\Company\CreateCompanyRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

/**
 * @group Company Managment
 * 
 */
class CreateCompanyCommand extends Controller
{
    private imageService $imageService ;
    public function __construct(imageService $_imageService)
    {
        $this->imageService = $_imageService ;
    }
    /**
     * Create/Store New Company .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Company\CompanyResource
     * @apiResourceModel App\Models\Company with=App\Models\ContactLink,App\Models\GalleryImage,App\Models\CompanyPhone
     * 
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     */
    public function __invoke(CreateCompanyRequest $request) 
    {
        DB::beginTransaction();
        
        $data = $request->validated();

        $s = microtime(true) ;
        $cloudinaryImage = $request->file('profile_image')?->storeOnCloudinary(CloudFolders::COMPANY) ?? null ;
            $p_url = $cloudinaryImage?->getSecurePath() ?? null ;
            $p_id = $cloudinaryImage?->getPublicId() ?? null  ;
        $cloudinaryImage = $request->file('background_image')?->storeOnCloudinary(CloudFolders::COMPANY) ?? null ;
            $b_url = $cloudinaryImage?->getSecurePath() ?? null ;
            $b_id = $cloudinaryImage?->getPublicId() ?? null ;
        $e = microtime(true) ;
       
        //var_dump(['time to store in cloudinary' => $e - $s] ) ;
        
        //create company
        $company = Company::create([
                'profile_image_url' =>  $p_url ,
                'profile_image_public_id' => $p_id ,

                'background_image_url' =>  $b_url ,
                'background_image_public_id' =>  $b_id ,
                
                'username' => auth()->user()->slug ,
                'name' => $data['name'] , 
                'description' => $data['description'] , 
                'size' => $data['size'] , 
                'city' => $data['city'] , 
                'region' => $data['region']  , 
                'street_address' => $data['street_address'] ,
                'industry_name' => $data['industry_name']  
        ]);

        
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

        if(array_key_exists('gallery_images' , $data))
        {
            $gallery_images = [] ;
        
            foreach($data['gallery_images'] as $galley_image)
            {
                $cloudinaryImage = Cloudinary::upload($galley_image->getRealPath() ,[
                    'folder' => CloudFolders::COMPANY
                ]);

                $gallery_images[] = new GalleryImage([
                    'url' => $cloudinaryImage->getSecurePath(),
                    'public_id' => $cloudinaryImage->getPublicId() ,
                ]);
                
            }
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
    }
}
