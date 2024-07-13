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
        //var_dump($roles);
        
        if(!auth('sanctum')->check()){
            return response()->json(
                ['message' => 'Unauthenticated.'] ,
                 401 ,
                ['Accept' => 'application/json']) ;
        }
        
        if(in_array('no_role' , $roles))
        {
            if(auth('sanctum')->user()->role_name != null)
            {
                return response()->json(
                    ['message' =>'bruh! you already have a role'] ,
                     403 ,
                     ['Accept' => 'application/json']) ;
            }
            return $next($request);
        }

        if(!in_array('any' , $roles) and !in_array(auth('sanctum')->user()->role_name , $roles)){
            return response()->json(
                ['message' => 'you don\'t have the currect role'] ,
                 403 ,
                 ['Accept' => 'application/json']) ;
        }

        return $next($request);
    }
}
