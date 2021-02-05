<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Cache;

class UserActivityStatus
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
        Cache::put('user-is-online-' . auth('api')->user()->id, true, Carbon::now()->addSeconds(20));
        return $next($request);
    }
}
