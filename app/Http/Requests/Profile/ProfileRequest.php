<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $max = 2048 ;

        if(request()->isMethod('post')){      
            return [
                'gender' => ['required' , 'string' , 'in:male,female'] ,
                'avatar_image'=> ['image' , 'min:10' , 'max:'.$max,'mimes:png,jpg'] ,
                'cover_image'=> ['image' , 'min:10' , 'max:'.$max,'mimes:png,jpg'] ,
            ];  
        }
        return [
            'avatar_image' => ['image' , 'max:.'.$max, 'mimes:png,jpg'] ,
            'cover_image' => ['image'  , 'max:.'.$max, 'mimes:png,jpg'] ,
        ];  
       
    }
}
