<?php

namespace App\Rules;

use Closure;
use App\Services\xmlService;
use Illuminate\Contracts\Validation\ValidationRule;

class SyrianCityRule implements ValidationRule
{

    protected array $cities = [];

    public function __construct()
    {
        $xmlService = new xmlService("constants/xml/syrian_cities.xml") ;
        
        $this->cities = $xmlService->toJson($xmlService->xmlContent)->city ;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, $this->cities)) {
            $fail("The selected city must be a valid syrian city.") ;
        }
    }
}
