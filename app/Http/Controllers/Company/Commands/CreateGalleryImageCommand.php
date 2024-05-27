<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Constants\Disks;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use App\Services\imageService;
use App\Constants\CloudFolders;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\GalleryImageResource;
use App\Http\Requests\Company\CreateCompanyImageRequest;

/**
 * @group Company Managment
 * 
 */
class CreateGalleryImageCommand extends Controller
{
    /**
     *create gallary image 
     *
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Company\GalleryImageResource
     * @apiResourceModel App\Models\GalleryImage 
     *
     * @return \Illuminate\Http\JsonResponse | GalleryImageResource
     **/
    public function __invoke(CreateCompanyImageRequest $request)
    {
        $company = Company::findOrFail(auth()->user()->role_id) ;

        $cloudinaryImage = $request->file('image')->storeOnCloudinary(CloudFolders::COMPANY) ;

        $gallery_image = GalleryImage::create([
            'url' => $cloudinaryImage->getSecurePath() ,
            'public_id' => $cloudinaryImage->getPublicId() ,
            'company_id' => $company->id ,
        ]);

        //just in case
        $company->gallery_images()->save($gallery_image) ;

        return GalleryImageResource::make($gallery_image)
            ->response()
            ->withHeaders(['Accept' => 'application/json']) ;
    }
}
