<?php

namespace App\Http\Requests\ClientOffer;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClientRejectProposalsRequest extends FormRequest
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
        return [
            'proposal_ids' => ['required' , 'array'] ,
            'proposal_ids.*' => [
                'required' ,
                'integer' ,
                Rule::exists('client_offer_proposals' , 'id')
                ->where('client_id' , auth('sanctum')->id())
                // ->whereNull('rejected_at')
                // ->whereNull('accepted_at'),
            ],
        ];
    }
}
