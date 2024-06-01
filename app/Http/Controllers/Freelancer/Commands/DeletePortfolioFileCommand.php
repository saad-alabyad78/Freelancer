<?php

namespace App\Http\Controllers\Freelancer\Commands;

use App\Models\File;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Freelancer\DeletePortfolioFileRequest;

/**
 *@group Freelancer Managment 
 **/
class DeletePortfolioFileCommand extends Controller
{
  
    /**
     * Delete New Portfolio's File .
     * 
     * @authenticated
     * 
     * 
     * @return \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
     * 
     */
    public function __invoke(DeletePortfolioFileRequest $request)
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

        $file = $portfolio->files()->find($request->file_id) ;

        if($file == null)
        {
            return response()->json([
                'message' => 'you do not have a file with id = ' . $request->file_id
            ] , 404);
        }

        $file->delete() ;

        return response()->json(['message' => 'deleted']) ;
    }
}
