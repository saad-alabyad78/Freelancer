<?php

namespace App\Http\Requests\Freelancer;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioRequest extends FormRequest
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
            'portfolio_id' => ['required'] ,
            'title' => ['string' , 'min:3' , 'max:255'] ,
            'url' => [
                'nullable',
                'string',
                'regex:/\b(?:https?|ftp):\/\/[a-zA-Z0-9-.]+\.[a-zA-Z]{2,}(?:\/\S*)?\b/',
            ],  
            'date' => ['date' , 'nullable'],
            'description' => ['string' , 'min:20'],

            'file_ids' => ['array' , 'max:6'] ,
            'file_ids.*' => ['required','exists:files,id'],

            'image_ids' => ['array' , 'max:6'] ,
            'image_ids.*' => ['required','exists:images,id'],

            'skill_ids' => ['required' , 'array' , 'min:5' , 'max:50'] ,
            'skill_ids.*' => ['exists:skills,id' , 'distinct'] ,
        ];
    }
}
