<?php

namespace App\Http\Controllers\Client;

use App\Models\Image;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\IClientRepository;
use App\Http\Resources\Client\ClientResource;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
/**
 * @group Client Managment
 * 
 **/
class ClientController extends Controller
{
    public function __construct(protected IClientRepository $clientRepository)
    {

    }
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

        if($data['profile_image_id'] ?? false)
            $data['profile_image_url'] = Image::findOrFail($data['profile_image_id'])->pluck('url')->first() ;
        if($data['background_image_id'] ?? false)
            $data['background_image_url'] = Image::findOrFail($data['background_image_id'])->pluck('url')->first();
        
        $data['username'] = auth('sanctum')->user()->slug ;

        try {       
            //create company
            $client = Client::create($data);

            $client->user()->save(auth('sanctum')->user()) ;

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
        $client = Client::findOrFail(auth('sanctum')->user()->role['id']) ;

        $client = $this->clientRepository->update($client , $request->validated()) ;

        return ClientResource::make($client) ;
    }
}
