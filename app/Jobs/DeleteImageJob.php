<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $disk ;
    public $image ;
    /**
     * Create a new job instance.
     */
    public function __construct(string $_disk , string $_image)
    {
        $this->disk = $_disk ;
        $this->image = $_image ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Storage::disk($this->disk)->delete($this->image) ;
    }
}
