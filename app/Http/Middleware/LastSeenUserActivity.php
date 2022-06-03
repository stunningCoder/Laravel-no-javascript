<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Session;
use Illuminate\Session\Store;
use App\Jobs\IsOffline;

class LastSeenUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *//*
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 30)) {
                // request 30 minates ago
                \App\User::find($request->user->id)->hasLeft();
                \Log::channel('custom-log')->info('destroy session now!');
                session_destroy();
                session_unset();
            }
            else {
                $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time
                \Log::channel('custom-log')->info($_SESSION['LAST_ACTIVITY']);
            }
        }
        return $next($request);
    }*/
    protected $session;
    protected $timeout = 30;
     
    public function __construct(Store $session){
        $this->session = $session;
    }
/*
    public function handle($request, Closure $next){
        if (now()->diffInSeconds(session('lastActivityTime')) >= (30) )
        {  // also you can this value in your config file and use here
           if (auth()->check() && auth()->id() > 1)
           {
               $user = auth()->user();
               auth()->logout();

               $user->update(['is_logged_in' => false]);
               $this->reCacheAllUsersData();

               session()->forget('lastActivityTime');

               return redirect(route('users.login'));
           }

        }
        return $next($request);
    }*/

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $expireTime = Carbon::now()->addMinute(1); // keep online for 1 min

            //------------Using Cache--------------//

            $vendorCache = 'is_online'.Auth::user()->id;
            if(Cache::has($vendorCache))
                Cache::forget($vendorCache);
            Cache::put($vendorCache, true, $expireTime);
            //Last Seen
            // User::where('id', Auth::user()->id)->update(['last_seen' => Carbon::now()]);*/

            //----------------------------------------//

            //-----------Using job---------------------//
/*
            \App\User::find($request->user()->getId())->hasVisited();
            IsOffline::dispatch($request->user_id)->delay($expireTime);
            \Log::channel('custom-log')->info(Auth::user()->id);*/

            //-----------------------------------------------//

        }
        return $next($request);
    }
}
