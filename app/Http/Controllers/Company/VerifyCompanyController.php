<?php

namespace App\Http\Controllers\Company;

use App\Models\File;
use App\Models\User;
use App\Models\Admin;
use App\Models\Company;
use App\Models\SuperAdmin;
use App\Models\Verification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Company\VerificationResource;
use App\Http\Requests\Company\StoreVerificationRequest;
use App\Http\Requests\Company\AcceptVerificationRequest;
use App\Http\Requests\Company\RejectVerificationRequest;
/**
 *@group Company Verification
 **/
class VerifyCompanyController extends Controller
{
    /**
     * return list of verification request (admin && company)
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $user = User::where('id' , auth('sanctum')->id())->first() ;

        $verifications = null ;

        if($user->role_type == Company::class)
        {
            $verifications = Verification::with('company')
                ->where('company_id' , $user->role_id)
                ->orderByDesc('created_at') ;
        }
        if($user->role_type == Admin::class or $user->role_type == SuperAdmin::class)
        {
            $verifications = Verification::with('company')
                ->whereNull(['rejected_at' , 'accepted_at'])
                ->orderByDesc('created_at') ;
        }

        return VerificationResource::collection($verifications->paginate()) ;
    }
    /**
     * show  (admin && company)
     * @return VerificationResource
     * @param \App\Models\Verification $verification
     */
    public function show(Verification $verification)
    {
        return VerificationResource::make($verification->load(['company' , 'file'])) ;
    }
    /**
     * store (company)
     * @param \App\Http\Requests\Company\StoreVerificationRequest $request
     * @return VerificationResource
     */
    public function store(StoreVerificationRequest $request)
    {
        $user = User::where('id' , auth('sanctum')->id())->first() ;
        
        $verification = Verification::create(['company_id' => $user->role_id]) ;

        File::where('id' , $request->input('file'))
        ->update([
            'filable_id' => $verification->id ,
            'filable_type' => Verification::class ,
        ]);
        

        return VerificationResource::make($verification->load(['company' , 'file'])) ;
    }
    /**
     * accept (admin)
     * 
     * @param \App\Models\Verification $verification
     * @param \App\Http\Requests\Company\AcceptVerificationRequest $request
     * @return VerificationResource
     */
    public function accept(Verification $verification , AcceptVerificationRequest $request)
    {
        $verification->update([
            'response' => $request->input('response') ,
            'accepted_at' => now()->toDateTimeString() ,
        ]) ;

        Company::where('id' , $verification->company_id)
        ->update(['verified_at' => now()->toDateTimeString()]) ;

        //todo send notification to company
        
        return VerificationResource::make($verification->load(['company' , 'file'])) ;
    }
    /**
     * reject (admin)
     * @param \App\Models\Verification $verification
     * @param \App\Http\Requests\Company\RejectVerificationRequest $request
     * @return VerificationResource
     */
    public function reject(Verification $verification , RejectVerificationRequest $request)
    {
        $verification->update([
            'response' => $request->input('response') ,
            'rejected_at' => now()->toDateTimeString() ,
        ]) ;
        
        return VerificationResource::make($verification->load(['company' , 'file'])) ;
    }
}
