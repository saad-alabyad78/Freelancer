<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Cloudinary\Api\Admin\AdminApi;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DeleteCloudinaryAssetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    public function __construct(public array $public_ids)
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        var_dump(['delete ids ' => $this->public_ids]);
        
        if(!empty($this->public_ids))
        {
            (new AdminApi())->deleteAssets($this->public_ids) ;
        }
    }
}
