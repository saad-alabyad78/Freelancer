<?php

namespace App\Rules;

use Closure;
use App\Services\xmlService;
use Illuminate\Contracts\Validation\ValidationRule;

class Job_OfferStatusRule implements ValidationRule
{
    protected array $statuses = [] ;
    public function __construct()
    {
        $xmlService = new xmlService('constants/xml/job_offer_status.xml') ;

        $statuses = $xmlService->toJson($xmlService->xmlContent)->status ;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!in_array($value , $this->statuses)){
            $fail('the selected status is not valid') ;
        }
    }

    public static function docs(): array
    {
        return [
            'description' => 'this must be a valid job offer status ',
            'example' => 'pending', // Only used if no other supported rules are present
        ];
    }
}
