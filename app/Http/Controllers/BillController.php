<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BillResource;

/**
 *@group Bills
 **/
class BillController extends Controller
{
    /**
     * All Bills
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $bills = Bill::query() ;

        return BillResource::collection($bills->paginate(25)) ;
    }

    /**
     * 
     * Reports
     * 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function reports()
    {
        // Get statistics for non-admins
        $nonAdminStats = DB::table('bills')
        ->select(
            DB::raw('DATE_TRUNC(\'week\', created_at) as week_start'),
            DB::raw('EXTRACT(YEAR FROM created_at) as year'),
            DB::raw('EXTRACT(WEEK FROM created_at) as week'),
            DB::raw('AVG(money) as avg_money')
        )
        ->where('to_type', '!=', 'admin')
        ->groupBy('week_start', 'year', 'week')
        ->get();

        // Get statistics for admins
        $adminStats = DB::table('bills')
        ->select(
            DB::raw('DATE_TRUNC(\'week\', created_at) as week_start'),
            DB::raw('EXTRACT(YEAR FROM created_at) as year'),
            DB::raw('EXTRACT(WEEK FROM created_at) as week'),
            DB::raw('AVG(money) as avg_money')
        )
        ->where('to_type', '=', 'admin')
        ->groupBy('week_start', 'year', 'week')
        ->get();

        return response()->json([
            'non_admin_stats' => $nonAdminStats ,
            'admin_stats' => $adminStats ,
        ]);
    }
}
