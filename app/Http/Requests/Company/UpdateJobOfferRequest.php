<?php

namespace App\Http\Requests\Company;

use App\Constants\Gender;
use App\Rules\GenderRule;
use App\Constants\LocationType;
use Illuminate\Validation\Rule;
use App\Constants\AttendanceType;
use App\Rules\FieldsTogetherOrNoneRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobOfferRequest extends FormRequest
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
            'job_offer_id' => ['required' , 'exists:job_offers,id'] ,
            
            'industry_name' => [ 'exists:industries,name' , 'string'] ,
            'job_role_id' => [ 'exists:job_roles,id'] ,
            
            'location_type' => [ 'string', Rule::in(LocationType::$types)],
            'attendance_type' => [ 'string', Rule::in(AttendanceType::$types)],

            'max_salary' => ['integer', 'min:0', 'max:100000000', 'gte:min_salary' , new FieldsTogetherOrNoneRule('min_salary')],
            'min_salary' => ['integer', 'min:0', 'max:100000000', 'lte:max_salary' , new FieldsTogetherOrNoneRule('max_salary')],

            'max_age' => ['integer', 'min:16', 'max:60', 'gte:min_salary' , new FieldsTogetherOrNoneRule('min_age')],
            'min_age' => ['integer', 'min:16', 'max:60', 'lte:max_salary' , new FieldsTogetherOrNoneRule('max_age')],
            
            'description' => ['string' ,  'min:10' ,' max:40000' ] ,

            'transportation' => [ 'bool'] ,
            'health_insurance' => [ 'bool'] ,
            'military_service' => [ 'bool'] ,
            'gender' => ['nullable' , Rule::in(Gender::$types)] ,
            
            'skill_ids' => ['array' , 'min:1' , 'max:25'] ,
            'skill_ids.*' => ['exists:skills,id'] ,

        ];
    }
}
