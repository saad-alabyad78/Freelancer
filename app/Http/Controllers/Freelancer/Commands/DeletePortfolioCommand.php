<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Freelancer\DeletePortfolioRequest;
/**
 *@group Freelancer Managment 
 **/
class DeletePortfolioCommand extends Controller
{
    /**
     * Delete Portfolio.
     * 
     * @authenticated
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function __invoke(DeletePortfolioRequest $request)
    {
        $portfolio = Portfolio::where([
            'id' => $request->portfolio_id ,
            'freelancer_id' => auth()->user()->role['id'] 
        ])->first() ;

        if($portfolio == null)
        {
            return response()->json([
                'message' => 'you do not have a portfolio with id = ' . $request->portfolio_id
            ] , 404);
        }

        $portfolio->delete() ;

        return response()->json(['message'=>'deleted']) ;
    }
}
