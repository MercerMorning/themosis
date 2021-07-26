<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Theme\Models\Thread;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;
use function foo\func;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::all();
        $alreadyHaveDialog = [];
        $personalThreads = ThreadParticipant::query()
            ->join('threads', 'participants_table_message.thread_id', '=', 'threads.id')
            ->where('user_id', wp_get_current_user()->ID)
            ->where('threads.private', 1)
            ->get(['threads.id', 'threads.subject', 'threads.created_at']);
        $personalThreads = $personalThreads->mapWithKeys(function ($item) use (&$alreadyHaveDialog){
            $dialogParticipant = ThreadParticipant::query()
                ->where('thread_id', $item->id)
                ->where('user_id', '!=', wp_get_current_user()->ID)
                ->first()
                ->user_id;
            $alreadyHaveDialog[] = $dialogParticipant;
            return [
                $item->id =>  [
                    'participant_id' =>
                        $dialogParticipant
                    ,
                    'created_at' => $item->created_at
                ],
            ];
        });


        $publicThreads = User::find(wp_get_current_user()->ID)
            ->threads()
            ->filter(function ($item) {
                if (!$item->private) {
                    return $item;
                }
            })
            ->each(function ($item) {
                return [
                    $item->id =>  [
                        'subject' => $item->subject,
                        'created_at' => $item->created_at,
                    ],
                ];
            });

//        $threads = $personalThreads->merge($publicThreads);
        $threads = array_merge($publicThreads->toArray(), $personalThreads->toArray());

        User::query()
            ->where('id', '!=', wp_get_current_user()->ID)
            ->whereNotIn('id', $alreadyHaveDialog)
            ->get()
            ->each(function ($user) use (&$threads){
                $threads[] = ['user_id' => $user->ID];
            });

        return view('front.chat', [
            'threads' => $threads,
            'users' => $users->toArray(),
        ]);
    }
}