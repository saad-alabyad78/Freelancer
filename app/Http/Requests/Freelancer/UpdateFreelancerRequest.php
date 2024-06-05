<?php

namespace App\Http\Requests\Freelancer;

use Carbon\Carbon;
use App\Constants\Gender;
use App\Rules\SyrianCityRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFreelancerRequest extends FormRequest
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

                'profile_image_url' => ['required_with:profile_image_id' ,'string' , 'nullable'] ,
                'profile_image_id' => ['required_with:profile_image_url' , 'exists:images,id' , 'nullable'] ,
                'background_image_url' => ['required_with:background_image_id' ,'strign' , 'nullable'] ,
                'background_image_id' => ['required_with:background_image_id' , 'exists:images,id' , 'nullable'] ,

                'headline' => [ 'string' , 'min:20' , 'max:200'],
                'description' => [ 'string' , 'min:60' , 'max:4000'],
                'city' => [ new SyrianCityRule()],
                'gender' => [ Rule::in(Gender::$types)],
                'date_of_birth' => [ 'date' , 'before_or_equal:' . Carbon::now()->subYears(16)->toDateString()],
                'job_role_id' => [ 'exists:job_roles,id'],
                'skill_ids' => ['array' , 'min:5' , 'max:50'] ,
                'skill_ids.*' => ['exists:skills,id' , 'distinct'] ,
                
        ];
    }
}
