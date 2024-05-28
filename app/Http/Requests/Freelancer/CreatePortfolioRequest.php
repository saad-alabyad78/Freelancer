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
            'url' => ['string'],
            'date' => ['date'],
            'description' => ['required' , 'string' , 'min:20'],
            'files' => ['array' , 'max:6'] ,
                'files.*' => [
                    'required',
                    'file',
                    'mimes:pdf,jpeg,png,gif,mp4,mov,mp3,wav,docx,txt,pptx,zip,html,css,js',
                    'max:20480' // The file size limit is 20MB
                ]
        ];
    }
}
