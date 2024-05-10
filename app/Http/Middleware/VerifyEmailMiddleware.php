<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        if(!auth()->check()){
            return response()->json(
                ['message' => 'Unauthenticated.'] ,
                 401 ,
                ['Accept' => 'application/json']) ;
        }
        
        if(!auth()->user()->email_verified_at){
                return response()->json(
                    ['message' => 'your email address is not verified'] ,
                     403 ,
                    ['Accept' => 'application/json']) ;
        }
        return $next($request);
    }
}
