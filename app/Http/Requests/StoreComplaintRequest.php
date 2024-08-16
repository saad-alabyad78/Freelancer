<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'reason' => 'required|string',
            'type' => 'nullable|string',
        ];
    }
}

