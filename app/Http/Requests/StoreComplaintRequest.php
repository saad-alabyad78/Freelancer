<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Constants\ComplaintTypes;
use Illuminate\Validation\Rule;

class StoreComplaintRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'accused_id' => 'required|exists:users,id',
            'type' => ['nullable', Rule::in(ComplaintTypes::all())],
            'reason' => 'required|string',
        ];
    }
}
