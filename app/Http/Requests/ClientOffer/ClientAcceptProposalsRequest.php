<?php

namespace App\Http\Requests\ClientOffer;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\ClientOfferProposal;
use Illuminate\Foundation\Http\FormRequest;

class ClientAcceptProposalsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = User::where('id' , auth('sanctum')->id())->first() ;
        
        return [
            'proposal_id' => [
            'required' ,
            'integer' ,
            Rule::exists('client_offer_proposals' , 'id')
                ->where('client_id' , $user?->role_id)
                ->whereNull('rejected_at')
                ->whereNull('accepted_at') ,
            ] ,
        ];
    }
}
