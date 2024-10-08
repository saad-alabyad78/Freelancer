<?php

namespace App\Http\Requests\JobOfferProposal;

use App\Models\Company;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FilterJobOfferProposalRequest extends FormRequest
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
            'job_offer_id' => [
                'nullable',
                'integer',
                Rule::exists('job_offers', 'id')->where(function ($query) {
                    $query->where('company_id', auth('sanctum')->user()->role_id);
                }),
            ],
            'order' => 'nullable|in:asc,desc',
        ];
    }
}
