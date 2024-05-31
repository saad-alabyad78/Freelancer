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
            'url' => [ 'nullable' , 'string' , 'regex:^(https?://)?([da-z.-]+).([a-z.]{2,6})([/w .-]*)*/?(?:?[^s]*)?(?:#[^s]*)?$'],
            'date' => ['date'],
            'description' => [ 'string' , 'min:20'],
        ];
    }
}
