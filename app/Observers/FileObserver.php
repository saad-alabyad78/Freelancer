<?php

namespace App\Observers;
use App\Models\File;
use App\Jobs\DeleteCloudinaryAssetsJob;

class FileObserver
{
    public function deleting(File $file)
    {
        DeleteCloudinaryAssetsJob::dispatch([$file->public_id]) ;
    }
}
