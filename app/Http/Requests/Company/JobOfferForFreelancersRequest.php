<?php

namespace App\Http\Requests\Company;

use App\Constants\Gender;
use App\Models\Freelancer;
use App\Constants\LocationType;
use Illuminate\Validation\Rule;
use App\Constants\AttendanceType;
use App\Constants\JobOfferStatus;
use Illuminate\Foundation\Http\FormRequest;

class JobOfferForFreelancersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true ;
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
            'attendance_type' => ['string', Rule::in(AttendanceType::$types)],
            'status' => ['string' , Rule::in([JobOfferStatus::ACTIVE , JobOfferStatus::CLOUSED])],
            'job_role_id' => ['integer' , 'exists:job_roles,id'] ,  
            'max_salary' => ['integer'] ,
            'min_salary' => ['integer'] ,
            'transportation' => ['boolean'] ,
            'health_insurance' => ['boolean'] ,
            'military_service' => ['boolean'] ,
            'max_age' => ['integer'] ,
            'min_age' => ['integer'],
            'gender' => ['nullable' , Rule::in(Gender::$types)],
            'industry_name' => ['string' , 'exists:industries,name'],
            'i_can_apply_for_it' => ['boolean'] ,
        ];
    }
}
