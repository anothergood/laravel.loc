<?php

namespace App\Http\Middleware;

use Closure;

class MailVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->verified == 0){
            return response(['message' => 'Forbidden'],403);
        }
        return $next($request);
    }
}
