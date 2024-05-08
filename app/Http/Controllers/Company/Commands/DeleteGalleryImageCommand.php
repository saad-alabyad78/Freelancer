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
    public function __invoke(Company $company , GalleryImage $galleryImage)
    {
        $galleryImage->delete() ;

        return response()->noContent() ;
    }
}
