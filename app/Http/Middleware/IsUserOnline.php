<?php
/**
 * Check user online.
 */

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class IsUserOnline
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
// If user logged then create cache data on the 5 minutes.
        if (is_user_logged_in()) {
            $user = wp_get_current_user()->ID;
            $expiresAt = Carbon::now()->addMinutes(5);
            Cache::put('user-is-online-' . $user, true, $expiresAt);
        }

        return $next($request);
    }
}