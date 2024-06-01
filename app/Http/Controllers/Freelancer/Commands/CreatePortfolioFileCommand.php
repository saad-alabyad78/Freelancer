<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\File;
use App\Models\Portfolio;
use App\Constants\CloudFolders;
use App\Http\Controllers\Controller;
use App\Http\Resources\Freelancer\PortfolioResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Http\Requests\Freelancer\CreatePortfolioFileRequest;
/**
 *@group Freelancer Managment 
 **/
class CreatePortfolioFileCommand extends Controller
{

    /**
     * Store/Update New Portfolio's File .
     * 
     * @authenticated
     * 
     * @apiResource App\Http\Resources\Freelancer\PortfolioResource with=App\Http\Resources\Category\SkillResource
     * @apiResourceModel App\Models\Portfolio with=App\Models\Skill,App\Models\File,App\Models\Image
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function __invoke(CreatePortfolioFileRequest $request)
    {
        $portfolio = Portfolio::where([
            'id' => $request->portfolio_id ,
            'freelancer_id' => auth()->user()->role['id'] 
        ])->first() ;

        if($portfolio == null)
        {
            return response()->json([
                'message' => 'you do not have a portfolio with id = ' . $request->portfolio_id
            ] , 404);
        }

        $cloudinaryImage = Cloudinary::uploadFile($request->file('file')->getRealPath(),[
            'folder' => CloudFolders::FREELANCER ,
            'use_filename' => true ,
            'unique_filename' => false ,
            'resource_type' => 'auto' ,
        ]);


        $fileModel = new File([
            'url' => $cloudinaryImage->getSecurePath(),
            'public_id' => $cloudinaryImage->getPublicId(),
            'size' =>  $cloudinaryImage->getSize(), 
            'extention' =>  $cloudinaryImage?->getExtension() ,
        ]);

        $portfolio->files()->save($fileModel) ;

        return PortfolioResource::make($portfolio->load(['files' , 'images']))
                ->response()
                ->setStatusCode(201)
                ->withHeaders(['Content-Type' => 'application/json']);
    }
}
