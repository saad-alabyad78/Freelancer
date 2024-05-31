<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\File;
use App\Models\Image;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Constants\CloudFolders;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Freelancer\PortfolioResource;
use App\Http\Requests\Freelancer\CreatePortfolioRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
/**
 *@group Freelancer Managment 
 **/
class CreatePortfolioCommand extends Controller
{
    public function __invoke(CreatePortfolioRequest $request)
    {
        DB::beginTransaction();
        
        $data = $request->validated();

        try {

            $portfolio = Portfolio::create([
                'title' => $data['title'] ,
                'description' => $data['description'] ,
                'date' => $data['date'] ?? null ,
                'url' => $data['url'] ?? null ,
                'freelancer_id' => auth()->user()->role['id'] ,
            ]);

            if(array_key_exists('files' , $data))
            {
                $fileModels = [] ;
                
                foreach($data['files'] as $file)
                {   
                    $cloudinaryImage = Cloudinary::uploadFile($file->getRealPath(),[
                        'folder' => CloudFolders::FREELANCER ,
                        'use_filename' => true ,
                        //'public_id' => $fullFilename ,
                        'unique_filename' => false ,
                        'resource_type' => 'auto' ,
                    ]);

        
                    $fileModels[] = new File([
                        'url' => $cloudinaryImage->getSecurePath(),
                        'public_id' => $cloudinaryImage->getPublicId(),
                        'size' =>  $cloudinaryImage->getSize(), 
                        'extention' =>  $cloudinaryImage?->getExtension() ,
                    ]);
                }
                
                $portfolio->files()->saveMany($fileModels) ;
            }
            if(array_key_exists('images' , $data))
            {
                $imageModels = [] ;
                
                foreach($data['images'] as $image)
                {
                
                    $cloudinaryImage = Cloudinary::upload($image->getRealPath() ,[
                        'folder' => CloudFolders::FREELANCER
                    ]);
                
                    $imageModels[] = new Image([
                        'url' => $cloudinaryImage->getSecurePath(),
                        'public_id' => $cloudinaryImage->getPublicId(),
                        'size' =>  $cloudinaryImage->getSize(), 
                        'extention' => $cloudinaryImage?->getExtension() ,
                    ]);
                }
                
                $portfolio->images()->saveMany($imageModels) ;
            }
            
            DB::commit() ;
 

            return PortfolioResource::make($portfolio->load(['files' , 'images']))
                ->response()
                ->setStatusCode(201)
                ->withHeaders(['Content-Type' => 'application/json']);

        } catch (\Throwable $th) {
            DB::rollBack() ;
            return response()->json([
                'err' => $th ,
                'message' => 'something went wrong' ,
                'error' => $th->getMessage() ,
                'line' => $th->getLine()
                ] , 400) ;
        }


    }
}