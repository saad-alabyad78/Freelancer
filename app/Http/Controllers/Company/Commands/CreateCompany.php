<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Models\ContactLink;
use Illuminate\Support\Str;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use App\Services\imageService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Requests\Company\CreateCompanyRequest;

class CreateCompany extends Controller
{
    private imageService $imageService ;
    public function __construct(imageService $_imageService)
    {
        $this->imageService = $_imageService ;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateCompanyRequest $request)
    {
        $data = $request->validated();
        
        //create company
        
        $company = Company::create([
                'username' => auth()->user()->username ,
                'name' => $data['name'] , 
                'description' => $data['description'] ?? '', 
                'size' => $data['size'] , 
                'city' => $data['city'] , 
                'region' => $data['region'] ?? '' , 
                'street_address' => $data['street_address'] ?? '', 
        ]);

        //create links 

        $contact_links = [] ;
        
        foreach($data['contact_links'] as $link_name)
        {
            $contact_links[] = new ContactLink(['name' => $link_name]) ;
        }
        $company->contact_links()->saveMany($contact_links) ;

        //store and create images

        $gallery_images = [] ;
        
        foreach($data['gallery_images'] as $galley_image)
        {
            $name = $this->imageService->store_image($galley_image , 'Company/Gallary');

            if($name)
            {
                $gallery_images[] = new GalleryImage(['name' => $name]);
            }
        }
        $company->gallery_images()->saveMany($gallery_images) ;

        
        return CompanyResource::make($company->with
            ([
                'contact_links' ,
                'images' ,
                'company_phones' ,
            ])) ;
    }
}
