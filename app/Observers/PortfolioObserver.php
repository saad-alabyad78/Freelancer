<?php

namespace App\Observers;
use App\Models\Portfolio;
use App\Jobs\DeleteCloudinaryAssetsJob;

class PortfolioObserver
{
    public function deleting(Portfolio $portfolio)
    {
        DeleteCloudinaryAssetsJob::dispatch($portfolio->files()->pluck('public_id')->toArray());
        DeleteCloudinaryAssetsJob::dispatch($portfolio->images()->pluck('public_id')->toArray());
    }
}
