<?php

namespace App\Rules;

use Closure;
use App\Services\xmlService;
use Illuminate\Contracts\Validation\ValidationRule;

class Job_OfferTypesRule implements ValidationRule
{
    protected array $types = [] ;
    public function __construct()
    {
        $xmlService = new xmlService('constants/xml/job_offer_types.xml') ;

        $this->types = $xmlService->toJson($xmlService->xmlContent)->type ;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!in_array($value , $this->types)){
            $fail('the selected type is not valid' . implode(' , ' , $this->types)) ;
        }
    }

    public static function docs(): array
    {
        return [
            'description' => 'this must be a valid job offer type ',
            'example' => 
            implode(' , ' , (xmlService::toJson(
                xmlService::read('constants/xml/job_offer_types.xml'
            )))->type), // Only used if no other supported rules are present
        ];
    }
}
