<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use App\Http\Resources\BillResource;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::query() ;

        return BillResource::collection($bills->paginate(25)) ;
    }
}
