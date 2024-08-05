<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
/**
 *@group Home
 **/
class HomeController extends Controller
{
    /**
     * website homepage info
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function home()
    {
        $data = [] ;

        $companies = Company::limit(10)->get() ;

        $products = Product::limit(10)->get() ;

        $data['companies'] = $companies; 
        $data['products'] = $products ;

        return response()->json($data) ;
    }
}
