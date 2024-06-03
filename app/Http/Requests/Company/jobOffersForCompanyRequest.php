<?php

namespace App\Http\Requests\Company;

use App\Constants\LocationType;
use Illuminate\Validation\Rule;
use App\Constants\AttendenceType;
use App\Constants\JobOfferStatus;
use App\Constants\Job_OfferStatus;
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
            'status' => ['string' , Rule::in(JobOfferStatus::$types)],
            'job_role_id' => ['integer' , 'exists:job_roles,id'] ,            
        ];
    }
}
