<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Http\Resources\Company\CompanyResource;
/**
 *@group Home
 **/
class HomeController extends Controller
{
    /**
     * website homepage info
     * 
     * @unauthenticated
     * 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function home()
    {
        $data = [] ;

        $companies = Company::limit(10)->get() ;

        $products = Product::limit(10)->get() ;

        $data['companies'] = CompanyResource::collection($companies); 
        $data['products'] = ProductResource::collection($products) ;

        return response()->json($data) ;
    }
}
