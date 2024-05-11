<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Constants\Disks;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use App\Services\imageService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\GalleryImageResource;
use App\Http\Requests\Company\CreateCompanyImageRequest;

/**
 * @group Company Managment
 * 
 */
class CreateGalleryImageCommand extends Controller
{
    public function __invoke(CreateCompanyImageRequest $request)
    {
        $company = Company::findOrFail(auth()->user()->role_id) ;

        $gallery_image = GalleryImage::create([
            'name' => imageService::store_image($request->validated()['image'] , Disks::COMPANY) ,
        ]);

        $company->gallery_images()->save($gallery_image) ;

        return GalleryImageResource::make($gallery_image)
            ->response()
            ->withHeaders(['Accept' => 'application/json']) ;
    }
}
