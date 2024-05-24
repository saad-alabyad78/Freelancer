<?php

namespace App\Http\Requests\Company;

use App\Rules\GenderRule;
use App\Constants\LocationType;
use Illuminate\Validation\Rule;
use App\Constants\AttendenceType;
use App\Rules\Job_OfferTypesRule;
use App\Constants\Job_OfferStatus;
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
            'location_type' => ['string', Rule::in(LocationType::$types)],
            'attendence_type' => ['string', Rule::in(AttendenceType::$types)],
            'status' => ['string' , Rule::in(Job_OfferStatus::$types)],
            'job_role' => ['string' , 'exists:job_roles,name'] ,            
        ];
    }
}
