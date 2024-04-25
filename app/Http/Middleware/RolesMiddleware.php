<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , ...$roles): Response
    {
        if(!auth()->check()){
            abort(401) ;
        }
        if(in_array('no_role' , $roles))
        {
            if(auth()->user()->role_name != null)
            {
                abort(401 , 'bruh! you already have a role') ;
            }
            return $next($request);
        }

        if(!in_array('any' , $roles) or !in_array(auth()->user()->role_name , $roles)){
            abort(401) ;
        }

        return $next($request);
    }
}
