<?php

namespace App\Http\Requests\Company;

use App\Constants\Gender;
use App\Constants\LocationType;
use Illuminate\Validation\Rule;
use App\Constants\AttendenceType;
use App\Constants\JobOfferStatus;
use Illuminate\Foundation\Http\FormRequest;

class JobOfferForFreelancersRequest extends FormRequest
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
            'company_id' => ['exists:companies,id'] ,
            'location_type' => ['string', Rule::in(LocationType::$types)],
            'attendence_type' => ['string', Rule::in(AttendenceType::$types)],
            'status' => ['string' , Rule::in([JobOfferStatus::AVTIVE , JobOfferStatus::CLOUSED])],
            'job_role_id' => ['integer' , 'exists:job_roles,id'] ,  
            // 'max_sallary' => ['integer'] ,
            // 'min_salary' => ['integer'] ,
            // 'transportation' => ['boolean'] ,
            // 'health_insurance' => ['boolean'] ,
            // 'military_service' => ['boolean'] ,
            // 'max_age' => ['integer'] ,
            // 'min_age' => ['integer'],
            'gender' => ['nullable' , Rule::in(Gender::$types)],
            'industry_name' => ['string' , 'exists:industries,name'],
            // 'military_service_required' => ['boolean'],
            // 'age_required' => ['boolean'] ,
            // 'gender_required' => ['boolean'] ,
            // 'i_can_apply_for_it' => ['boolean'] ,
        ];
    }
}
