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
        DeleteCloudinaryAssetsJob::dispatch($galleryImage->public_id) ;
    }
}
