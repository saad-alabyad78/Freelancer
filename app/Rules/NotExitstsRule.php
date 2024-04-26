<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\ValidationRule;

class NotExitstsRule implements ValidationRule
{
    private string $table;
    private string $column;

    public function __construct(string $table , string $column)
    {
        $this->table = $table ;
        $this->column = $column ;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnt = DB::table($this->table) //table
            ->where($this->column , '=' , $value)
            ->count() ;
        if($cnt){
            $fail($value . ' should not be found in database ') ;
        }
    }

    public static function docs(): array
    {
        return [
            'description' => 'value must be in database  ' ,
            'example' => '52KB', // Only used if no other supported rules are present
        ];
    }
}
