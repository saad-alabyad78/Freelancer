<?php

namespace App\Http\Controllers\Storage;

use App\Models\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Storage\FileResource;
use App\Http\Requests\Storage\StoreFileRequest;
/**
 * @group Storage Managment
 */
class StoreFileController extends Controller
{
    /**
     * Store File
     */
    public function __invoke(StoreFileRequest $request)
    {
        // $file = $request->file('file') ;
        // $filePath = $file->store('public/files');
        // $size = Storage::size($filePath);
        // $extension = $file->getClientOriginalExtension();
        // $url = Storage::url($filePath);
        
        // $file = File::create([
        //     'url' => $url ,
        //     'size' => $size ,
        //     'extention' => $extension ,
        // ]) ;

        $cloudResponse = $request->file('file')->storeOnCloudinary() ;

        $file = File::create([
        'title' => $request->only('title')['title'] ,
        'public_id' => $cloudResponse->getPublicId() ,
        'url' => $cloudResponse->getSecurePath() ,
        'size' => $cloudResponse->getSize() , 
        'extention' => $cloudResponse->getExtension() ,
        'deleted' => false ,
        ]) ;

        return FileResource::make($file) ;
    }
}
