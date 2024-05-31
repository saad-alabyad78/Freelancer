<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\Image;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Freelancer\DeletePortfolioImageRequest;

class DeletePortfolioImageCommand extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DeletePortfolioImageRequest $request)
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

        $image = Image::find($request->image_id) ;

        if($image == null)
        {
            return response()->json([
                'message' => 'you do not have a image with id = ' . $request->image_id
            ] , 404);
        }

        $image->delete() ;

        return response()->json(['message' => 'deleted']) ;
    }
}
