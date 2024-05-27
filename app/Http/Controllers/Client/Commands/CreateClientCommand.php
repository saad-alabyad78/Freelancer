<?php

namespace App\Http\Controllers\Client\Commands;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Constants\CloudFolders;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\ClientResource;
use App\Http\Requests\Client\CreateClientRequest;
/**
 * @group Client Managment
 * 
 **/
class CreateClientCommand extends Controller
{
    /**
     * Store New Client .
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
    public function __invoke(CreateClientRequest $request)
    {
        DB::beginTransaction();
        
        $data = $request->validated();

        try {
            $s = microtime(true) ;
            $cloudinaryImage = $request->file('profile_image')?->storeOnCloudinary(CloudFolders::CLIENT) ?? null ;
                $p_url = $cloudinaryImage?->getSecurePath() ?? null ;
                $p_id = $cloudinaryImage?->getPublicId() ?? null  ;
            $cloudinaryImage = $request->file('background_image')?->storeOnCloudinary(CloudFolders::CLIENT) ?? null ;
                $b_url = $cloudinaryImage?->getSecurePath() ?? null ;
                $b_id = $cloudinaryImage?->getPublicId() ?? null ;
            $e = microtime(true) ;
       
            
            
            //create company
            $client = Client::create([
                    'profile_image_url' =>  $p_url ,
                    'profile_image_public_id' => $p_id ,

                    'background_image_url' =>  $b_url ,
                    'background_image_public_id' =>  $b_id ,
                    
                    'username' => auth()->user()->slug ,
                    'city' => $data['city'] , 
                    'date_of_birth' => $data['date_of_birth'] ,
                    'gender' => $data['gender'] ,
            ]);

            $client->user()->save(auth()->user()) ;

            DB::commit() ;
        
            return ClientResource::make($client)
                ->response()
                ->setStatusCode(201)
                ->withHeaders(['Content-Type' => 'application/json']);

        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'message' => 'something went wrong' ,
                'error' => $th->getMessage() 
                ] , 400) ;
        }
    }
}
