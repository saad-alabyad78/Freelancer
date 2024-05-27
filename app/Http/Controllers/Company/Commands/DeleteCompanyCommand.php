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
     * 
     * @authenticated
     * 
     * return 422 if password is incurrect
     * 
     *  @return \Illuminate\Http\Response | \Illuminate\Routing\ResponseFactory
     */
    public function __invoke(DeleteCompanyRequest $request)
    {
        $password = $request->validated()['password'] ;

        if(!Hash::check($password , auth()->user()->getAuthPassword())){
            return response()->json([
                'error' => 'wrong password'
            ] , 422 );
        }

        $company = Company::findOrFail(auth()->user()->role_id) ;

        $company->delete() ;

        return response('deleted') ;
    }
}
