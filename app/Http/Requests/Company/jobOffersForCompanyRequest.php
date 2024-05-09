<?php

namespace App\Http\Requests\Company;

use App\Rules\GenderRule;
use App\Rules\Job_OfferTypesRule;
use App\Rules\Job_OfferStatusRule;
use Illuminate\Foundation\Http\FormRequest;

class jobOffersForCompanyRequest extends FormRequest
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
            'type' => ['string', new Job_OfferTypesRule()],
            'status' => ['string' , new Job_OfferStatusRule()],
            'job_role' => ['string' , 'exists:job_roles,name'] ,            
        ];
    }
}
