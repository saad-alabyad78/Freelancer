<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\DeleteGalleryImageRequest;

/**
 * @group Company Managment
 * 
 */
class DeleteGalleryImageCommand extends Controller
{
    public function __invoke(DeleteGalleryImageRequest $request)
    {
        $company = Company::findOrFail(auth()->user()->role_id); 
        
        $galleryImage = $company->gallery_images()
            ->findOrFail($request->validated()['gallery_images_id']) ;
        
        $galleryImage->delete() ;

        return response()->noContent() ;
    }
}
