<?php
namespace Theme\Services;


use Carbon\Carbon;
use Theme\Models\Thread;
use Theme\Models\ThreadMessage;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;

class UserPrepareService
{
    static public function  threadPresenterData($userId)
    {
        $userData = [
            'first_name' => get_user_meta( $userId, 'first_name', true ),
            'last_name' => get_user_meta( $userId, 'last_name', true ),
            'ava' => get_user_meta( $userId, 'avatar', true ) == '^ ""'
                ? get_user_meta( $userId, 'avatar', true ) :
                get_template_directory_uri() . '/assets/images/ava.png'
        ];
        return $userData;
    }

    static public function threadPresenterLastMessage($userId, $message)
    {
        $lastMessageAuthorData = self::threadPresenterData($userId);
        return $lastMessageAuthorData['first_name'] . ' ' . $lastMessageAuthorData['last_name'] .
        ': ' . $message;
    }

    static public function currentUserPresenter()
    {
        $currentUserId = wp_get_current_user()->ID;
        $userData = [
            'id' => $currentUserId,
            'first_name' => get_user_meta( $currentUserId, 'first_name', true ),
            'last_name' => get_user_meta( $currentUserId, 'last_name', true ),
            'ava' => get_user_meta( $currentUserId, 'avatar', true ) == '^ ""'
                ? get_user_meta( $currentUserId, 'avatar', true ) :
                get_template_directory_uri() . '/assets/images/ava.png'
        ];
        return $userData;
    }
}