<?php

namespace App\Observers;

use App\Models\Client;
use App\Jobs\DeleteCloudinaryAssetsJob;

class ClientObserver
{
    
    public function deleting(Client $client): void
    {
        var_dump('deleting client observer');
        
        DeleteCloudinaryAssetsJob::dispatchIf(
            $client->profile_image_public_id 
            ||
            $client->background_image_public_id 
        ,[
            $client->profile_image_public_id ,
            $client->background_image_public_id ,
        ]);

        $client->user()->delete() ;
    }

    
}
