<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\UpdateCompanyRequest;

/**
 * @group Company Managment
 * 
 */
class UpdateCompanyCommand extends Controller
{
    public function __invoke(Company $company , UpdateCompanyRequest $request)
    {
        //TODO:
    }
}
