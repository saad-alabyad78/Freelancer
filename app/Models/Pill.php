<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\Auth\UserResource;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pill extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
      'from_id',
      'from_type',
      'to_id',
      'to_type',
      'description',
      'money',  
    ];

    protected $appends = ['from' , 'to'] ;
    protected $userTypes = 
    [
        'clients' => Client::class,
        'admins' => Admin::class,
        'freelancers' => Freelancer::class,
        'super_admins' => SuperAdmin::class,
        'companies'=> Company::class
        ] ;

    public function getFromAttribute()
    {   
        $res = DB::table($this->from_type)
        ->where('id' , $this->from_id)
        ->first() ;

        $res = collect($res) ;

        $res['user'] = null ;

        if(array_key_exists($this->from_type , $this->userTypes))
        {
            $user = User::
              where('role_type' , $this->userTypes[$this->from_type])
            ->where('role_id' , $this->from_id)
            ->first()  ;

            $res['users'] = UserResource::make($user) ;
        }

        return $res ;
    }
    public function getToAttribute()
    {
        $res = DB::table($this->to_type)
        ->where('id' , $this->to_id)
        ->first() ;

        $res = collect($res) ;
        
        $res['user'] = null ;

        if(array_key_exists($this->to_type , $this->userTypes))
        {
            $user = User::
              where('role_type' , $this->userTypes[$this->from_type])
            ->where('role_id' , $this->from_id)
            ->first()  ;

            $res['users'] = UserResource::make($user) ;
        }

        return $res ;
    }
}
