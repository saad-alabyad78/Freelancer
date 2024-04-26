<?php

namespace App\Http\Controllers\Company\Query;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\imageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\GetGalleryImageRequest;
/**
 * @group Company Managment
 * 
 **/
class GalleryImageQuery extends Controller
{
    private imageService $imageService ;

    public function __construct(imageService $_imageService)
    {
        $this->imageService = $_imageService ;
    }

    /**
     * Get A Gallary Image
     **/
    public function __invoke(GetGalleryImageRequest $request)
    {
        $image_name = $request->validated()['image'] ;

        $disk = 'company' ;

        return new Response(
            $this->imageService->get_image($disk , $image_name) , 
            200 ,
            ['Content-Type' => $this->imageService->get_type($disk , $image_name)] 
        ) ;
    }
}
