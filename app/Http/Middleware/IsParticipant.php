<?php
/**
 * Check user online.
 */

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Theme\Helpers\AuthUser;
use Theme\Models\Thread;

class IsParticipant
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
        if (is_user_logged_in()) {
            if ($request->route()->getName() == "get_thread") {
                $threadParticipants = Thread::find($request->get('id'))
                    ->participants()->get()->pluck('user_id')->toArray();
                if (in_array(AuthUser::currentUserId(), $threadParticipants)) {
                    return $next($request);
                };
            }
        } else {
            if ($request->route()->getName() == "send_message_to_thread") {
                $threadParticipants = Thread::find($request->get('thread_id'))
                    ->participants()->get()->pluck('user_id')->toArray();
                if (in_array($request->get('user_id'), $threadParticipants)) {
                    return $next($request);
                }
            }
        }
    }
}