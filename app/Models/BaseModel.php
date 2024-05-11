<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassAssignmentException;

class BaseModel extends Model
{
    private $allowed = [
        'updated_at' ,
        'created_at' ,
        'id' ,
    ];

    //from [*] to []
    protected $guarded = [] ;
    protected $fillable = [] ;
    
     /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        throw_unless($this->isFillable($key) or in_array($key , $this->allowed ), 
        new MassAssignmentException('the ' . $key . ' attributes is not fillable  ' .
        implode(' . ' , $this->getFillable()) .
        ' Model => ' . get_class($this))) ;

        throw_if($this->isGuarded($key)  ,
        new MassAssignmentException('the ' . $key . ' attributes is guarded ' .
        implode(' . ' ,$this->getGuarded()).
        ' Model => ' . get_class($this))) ;

    
        return parent::setAttribute($key,$value) ;
    }
}
