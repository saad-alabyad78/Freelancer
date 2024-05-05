<?php

namespace App\Rules;


use Closure;
use App\Constants\Gender;
use Illuminate\Contracts\Validation\ValidationRule;

class GenderRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!empty($value) && ($value == Gender::MALE || $value == Gender::FEMALE)){
            $fail('must be a ' . Gender::MALE . ' or ' . Gender::FEMALE) ;
        }
    }

    public static function docs(): array
    {
        return [
            'description' => 'must be a ' . Gender::MALE . ' or ' . Gender::FEMALE ,
            'example' => 'male' ,
        ];
    }
}
