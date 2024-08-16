<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use Illuminate\Http\Request;
use App\Http\Resources\RateResource;
use App\Http\Requests\Rate\StoreRateRequest;

class RateController extends Controller
{
    public function store(StoreRateRequest $request)
    {
        $rate = Rate::create($request->validated()) ;

        return RateResource::make($rate) ;
    }
}
