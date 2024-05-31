<?php

namespace App\Observers;
use App\Models\Image;
use App\Jobs\DeleteCloudinaryAssetsJob;

class ImageObserver
{
    public function deleting(Image $image)
    {
        DeleteCloudinaryAssetsJob::dispatch([$image->public_id]) ;
    }
}
