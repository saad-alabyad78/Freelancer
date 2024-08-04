<?php

namespace App\Http\Requests\ClientOffer;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class GetProposalsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_offer_id' => [
                'required' , 
                'integer' ,
                Rule::exists('client_offers' , 'id')
                ->where('client_id' , auth('sanctum')->user()?->role_id) ,
            ] ,
            'orderByPrice' => ['boolean'] ,
            'orderByDays' => ['boolean'] ,
        ];
    }
}
