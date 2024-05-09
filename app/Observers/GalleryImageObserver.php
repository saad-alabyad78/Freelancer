<?php

namespace App\Observers;


use App\Constants\Disks;
use App\Models\GalleryImage;
use App\Services\imageService;

class GalleryImageObserver
{
    /**
     * Handle the GalleryImage "deleted" event.
     */
    public function deleting(GalleryImage $galleryImage): void
    {
        // in gallery images there is no defaults
        (new imageService())->delete(Disks::COMPANY , $galleryImage->name) ;
    }
}
