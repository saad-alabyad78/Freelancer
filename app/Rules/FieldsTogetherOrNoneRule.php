<?php

namespace App\Rules;

use Closure;
use Exception; 
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class FieldsTogetherOrNoneRule implements ValidationRule , DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];
    protected array $fields = [] ;

    public function __construct(...$params)
    {
        if(is_array($params[0] && count($params)==1))
        {
            $this->fields = $params[0] ;
        } 
        elseif(is_array($params))
        {
            $this->fields = $params ;
        }
        else{
            throw new Exception("FieldsTogetherOrNoneRule class", 500);
        }
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //To chick:what if the fields are not provided//
        
            //the rest must be empty
            foreach($this->fields as $field)
            {

                if((($this->data[$field] ?? null) != null ) == (!$value) ){
                    $fail('fields ' . $attribute . ' and ' . $field . ' must be either together or none ') ;
                }
            }
     
    }
    
    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }

    public static function docs(): array
    {
        return [
            'description' => 'fields must be either together or none ',
            'example' => ' '
        ];
    }
}
