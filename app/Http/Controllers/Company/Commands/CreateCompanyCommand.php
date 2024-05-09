<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Models\Industry;
use App\Models\ContactLink;
use App\Models\CompanyPhone;
use App\Models\GalleryImage;
use App\Services\imageService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Requests\Company\CreateCompanyRequest;

/**d
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
     * @apiResourceModel App\Models\Company with=ContactLink,GalleryImage,CompanyPhone
     * 
     * 
     * @return CompanyResource
     * 
     **/
    public function __invoke(CreateCompanyRequest $request , Industry $industry) 
    {
        $data = $request->validated();
        
        //create company
        $company = Company::create([
                'profile_image' => $this->imageService->store_image($data['profile_image'] ?? null , 'company') ,
                'background_image' => $this->imageService->store_image($data['background_image'] ?? null , 'company') ,
                'username' => auth()->user()->slug ,
                'name' => $data['name'] , 
                'description' => $data['description'] , 
                'size' => $data['size'] , 
                'city' => $data['city'] , 
                'region' => $data['region']  , 
                'street_address' => $data['street_address'] ,
                'industry_name' => $industry->name 
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
                $name = $this->imageService->store_image($galley_image , 'company');

                if($name)
                {
                    $gallery_images[] = new GalleryImage(['name' => $name]);
                }
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
        
        return CompanyResource::make($company->with
            ([
                'contact_links' ,
                'gallery_images' ,
                'company_phones' ,
            ])->first()) ;
    }
}
