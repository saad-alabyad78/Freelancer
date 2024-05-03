<?php

namespace App\Observers;

use App\Models\GalleryImage;
use App\Services\imageService;

class GalleryImageObserver
{
    /**
     * Handle the GalleryImage "deleted" event.
     */
    public function deleted(GalleryImage $galleryImage): void
    {
        (new imageService())->delete('company' , $galleryImage->name) ;
    }
}
