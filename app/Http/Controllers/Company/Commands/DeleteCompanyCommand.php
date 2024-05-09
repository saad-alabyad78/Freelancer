<?php

namespace App\Http\Controllers\Company\Commands;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Company\DeleteCompanyRequest;

/**
 * @group Company Managment
 * 
 */
class DeleteCompanyCommand extends Controller
{
    /**
     * Delete the company.
     * Note: the user will be deleted 
     */
    public function __invoke(Company $company , DeleteCompanyRequest $request)
    {
        $password = $request->validated()['password'] ;

        if(!Hash::check($password , auth()->user()->getAuthPassword())){
            return response()->json([
                'error' => 'wrong password'
            ]);
        }

        $company->delete() ;

        return response()->noContent() ;
    }
}
