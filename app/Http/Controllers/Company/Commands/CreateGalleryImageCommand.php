<?php

namespace App\Http\Controllers\Company\Commands;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CreateCompanyImageRequest;

/**
 * @group Company Managment
 * 
 */
class CreateGalleryImageCommand extends Controller
{
    public function __invoke(CreateCompanyImageRequest $request)
    {
        //TODO:
    }
}
