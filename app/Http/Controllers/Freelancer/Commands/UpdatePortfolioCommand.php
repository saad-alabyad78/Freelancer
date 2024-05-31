<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Freelancer\PortfolioResource;
use App\Http\Requests\Freelancer\UpdatePortfolioRequest;

class UpdatePortfolioCommand extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdatePortfolioRequest $request)
    {
        $portfolio = Portfolio::where([
            'id' => $request->portfolio_id ,
            'freelancr_id' => auth()->user()->role['id'] 
        ])->first() ;

        if($portfolio == null)
        {
            return response()->json([
                'message' => 'you do not have a portfolio with id = ' . $request->portfolio_id
            ] , 404);
        }

        $portfolio->update($request->validated()) ;

        return PortfolioResource::make($portfolio->load(['files' , 'images']))
                ->response()
                ->setStatusCode(201)
                ->withHeaders(['Content-Type' => 'application/json']);
    }
}
