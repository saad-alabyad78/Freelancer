<?php

namespace App\Http\Requests\Freelancer;

use Illuminate\Foundation\Http\FormRequest;

class CreatePortfolioImageRequest extends FormRequest
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
                'image' => [
                    'required',
                    'image',
                    'mimes:jpeg,png,jpg',
                    'max:10240' // The file size limit is 10MB
                ] ,
                'portfolio_id' => [
                    'required' ,
                    'exists:portfolios,id' ,
                ] ,
        ];
    }
}
