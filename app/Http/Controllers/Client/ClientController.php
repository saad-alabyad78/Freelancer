<?php

namespace App\Http\Controllers\Client;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientResource;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
/**
 * @group Client Managment
 * 
 **/
class ClientController extends Controller
{
    /**
     * Show Client .
     * 
     * 
     * @apiResource App\Http\Resources\Client\ClientResource
     * @apiResourceModel App\Models\Client
     * 
     * 
     * @return \App\Http\Resources\Client\ClientResource
     * 
     */
    public function show(Client $client)
    {
        return ClientResource::make($client) ;
    }
     /**
     * Store New Client .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Client\ClientResource
     * @apiResourceModel App\Models\Client
     * 
     * 
     * @return \App\Http\Resources\Client\ClientResource
     * 
     */
    public function store(CreateClientRequest $request)
    {
        DB::beginTransaction();

        $data = $request->validated();
        
        $data['username'] = auth()->user()->slug ;

        try {       
            //create company
            $client = Client::create($data);

            $client->user()->save(auth()->user()) ;

            DB::commit() ;
        
            return ClientResource::make($client) ;

        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'message' => 'something went wrong' ,
                'error' => $th->getMessage() 
                ] , 400) ;
        }
    }
    /**
     * Update Client .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Client\ClientResource
     * @apiResourceModel App\Models\Client
     * 
     * 
     * @return \App\Http\Resources\Client\ClientResource
     * 
     */
    public function update(UpdateClientRequest $request)
    {
        $client = Client::findOrFail(auth()->user()->role['id']) ;

        $client->update($request->validated());

        return ClientResource::make($client) ;
    }
}
