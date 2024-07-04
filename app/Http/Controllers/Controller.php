<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $user;
    protected $user_id;


    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = $request->user();

            $this->user_id = $this->user ? $this->user->id : null;  
              
            return $next($request);
        });
    }
}
