<?php

namespace Theme\Services;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Theme\Helpers\AuthUser;
use Theme\Models\User;
use Theme\Models\UserToken;

class ApiTokenService extends Controller
{
    /**
     * Update the authenticated user's API token.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public static function update()
    {
        $token = Str::random(60);
        if (!AuthUser::currentUser()->token()) {
            UserToken::create([
                'user_id' => AuthUser::currentUserId(),
                'api_token' => hash('sha256', $token),
            ]);
        } else {
            UserToken::query()
                ->where('user_id', AuthUser::currentUserId())
                ->update(['api_token' => hash('sha256', $token)]);
        }

        return ['token' => $token];
    }
}