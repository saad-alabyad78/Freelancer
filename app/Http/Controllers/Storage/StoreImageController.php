<?php

namespace App\Http\Controllers\Storage;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Services\imageService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Storage\ImageResource;
use App\Http\Requests\Storage\StoreImageRequest;
/**
 * @group Storage Managment
 */
class StoreImageController extends Controller
{
    private imageService $imageService ;
    
    public function __construct(imageService $_imageService)
    {
        $this->imageService = $_imageService ;
    }

    /**
     * Store Image.
     */
    public function __invoke(StoreImageRequest $request)
    {
        $name = $this->imageService->store_image($request->file('image') , 'public') ;

        $image = Image::create([
            'url' =>  $this->imageService::image_url($name) ,
            'deleted' => false ,
        ]);

        return ImageResource::make($image) ;
    }
}
