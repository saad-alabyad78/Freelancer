<?php

namespace App\Observers;


use App\Constants\Disks;
use App\Models\GalleryImage;
use App\Services\imageService;
use App\Jobs\DeleteCloudinaryAssetsJob;

class GalleryImageObserver
{
    /**
     * Handle the GalleryImage "deleted" event.
     */
    public function deleting(GalleryImage $galleryImage): void
    {
        var_dump('gallery image observer ' . $galleryImage->public_id ) ;
        $array = [$galleryImage->public_id] ;
        DeleteCloudinaryAssetsJob::dispatchIf(true , $array ) ;
    }
}
