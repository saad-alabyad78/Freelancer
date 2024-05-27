<?php

namespace App\Http\Controllers\Freelancer\Commands;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePortfolioRequest;

class CreatePortfolioCommand extends Controller
{
    public function __invoke(CreatePortfolioRequest $request)
    {
        return $data = $request->validated() ;
    }
}
