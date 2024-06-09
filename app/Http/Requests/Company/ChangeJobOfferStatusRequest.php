<?php

namespace App\Http\Requests\Company;

use Illuminate\Validation\Rule;
use App\Constants\JobOfferStatus;
use Illuminate\Foundation\Http\FormRequest;

class ChangeJobOfferStatusRequest extends FormRequest
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
            'job_offer_id' => ['required' , 'exists:job_offers,id'] ,
            'status' => ['required' , Rule::in(JobOfferStatus::$types)] ,
        ];
    }
}
