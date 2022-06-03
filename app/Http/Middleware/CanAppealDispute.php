<?php

namespace App\Http\Middleware;

use Closure;
use App\Purchase;

class CanAppealDispute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next )
    {
    	if( auth() -> check() && (
    		auth()->user()->isAdmin() || auth()->user()-> hasPermission('disputeappeals') ) )
	    {
		    return $next($request);
	    }
     
	
	    return redirect() -> route('home');
    }
}
