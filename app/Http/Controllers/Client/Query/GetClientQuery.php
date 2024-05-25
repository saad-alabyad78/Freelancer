<?php

namespace App\Http\Controllers\Client\Query;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientResource;
/**
 * @group Client Managment
 * 
 **/
class GetClientQuery extends Controller
{
    public function __invoke(Client $client)
    {
        return ClientResource::make($client) ;
    }
}
