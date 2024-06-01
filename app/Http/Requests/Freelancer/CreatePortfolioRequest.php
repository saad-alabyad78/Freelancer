<?php

namespace App\Http\Requests\Freelancer;

use Illuminate\Foundation\Http\FormRequest;

class CreatePortfolioRequest extends FormRequest
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
            'title' => ['required' , 'string' , 'min:3' , 'max:255'] ,
            'url' => [
                'nullable',
                'string',
                'regex:/\b(?:https?|ftp):\/\/[a-zA-Z0-9-.]+\.[a-zA-Z]{2,}(?:\/\S*)?\b/',
            ],  
            'date' => ['date'],
            'description' => ['required' , 'string' , 'min:20'],
            'files' => ['array' , 'max:6'] ,
                'files.*' => [
                    'required', 
                    'file',
                    'mimes:pdf,zip',
                    'max:10240' // The file size limit is 10MB
                ] ,
            'images' => ['array' , 'max:6'] ,
                'images.*' => [
                    'required',
                    'image',
                    'mimes:jpeg,png,jpg',
                    'max:10240' // The file size limit is 10MB
                ],
            'skills' => ['required' , 'array' , 'min:5' , 'max:50'] ,
            'skills.*' => ['string' , 'exists:skills,name' , 'distinct'] ,
        ];
    }
}
