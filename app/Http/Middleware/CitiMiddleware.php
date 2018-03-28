<?php

namespace App\Http\Middleware;

use Closure;

class CitiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     
	public function handle($request, Closure $next, $user)
	{
		echo "Citi User: ".$user;
		return $next($request);
	}
}
