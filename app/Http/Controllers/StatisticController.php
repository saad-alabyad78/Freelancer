<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 *@group Statistics
 **/
class StatisticController extends Controller
{
    /**
     * Roles Count
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function rolesCount()
    {
        $tables = [
            'companies' ,
            'freelancers' ,
            'clients' ,
            'skills' , 
            'job_roles' ,
            'categories' , 
            'sub_categories' ,
            'industries' ,
            'products' , 
            'client_offers' ,
            'job_offers' ,
        ] ;
  
        $data = [] ;

        foreach($tables as $table){
            $data[] = [
                'title' => $table ,
                'count' => DB::table($table)->count() ,
            ] ;
        }

        return response()->json($data) ;
    }
}
