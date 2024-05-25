<?php

namespace App\Http\Controllers\Client\Commands;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientResource;
use App\Http\Requests\Client\UpdateClientRequest;
/**
 * @group Client Managment
 * 
 **/
class UpdateClientCommand extends Controller
{
    /**
     * Update Company .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Client\ClientResource
     * @apiResourceModel App\Models\Client
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function __invoke(UpdateClientRequest $request)
    {
        $client = Client::findOrFail(auth()->user()->role['id']) ;

        $client->update($request->validated());

        return ClientResource::make($client)
            ->response()->withHeaders(['Content-Type' => 'application/json']) ;
    }
}
