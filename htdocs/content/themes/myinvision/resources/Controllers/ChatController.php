<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Theme\Models\Thread;
use Theme\Models\ThreadMessage;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;
use function foo\func;

class ChatController extends Controller
{
    public function index()
    {
//        $currentUser = User::find(wp_get_current_user()->ID);
//        $threadCon = new ThreadController();
//        dd($threadCon->getThreadMessages(new Request(['thread_id' => 44])));

//        $users = User::query()->join('usermeta', 'users.ID', '=', 'usermeta.user_id')->get();
        $users = User::all();
        $users = $users->mapWithKeys(function ($user) {
            $user->first_name = get_user_meta( $user->ID, 'first_name', true );
            $user->last_name = get_user_meta( $user->ID, 'last_name', true );
//            return $user;
            return [$user->ID => $user];

        })->toArray();
        $alreadyExistDialogs = [];
        $personalThreads = ThreadParticipant::query()
            ->join('threads', 'participants_table_message.thread_id', '=', 'threads.id')
            ->where('user_id', wp_get_current_user()->ID)
            ->where('threads.private', 1)
            ->get(['threads.id', 'threads.subject', 'threads.created_at']);
        $personalThreads = $personalThreads->mapWithKeys(function ($item) use (&$alreadyExistDialogs){
            $dialogParticipant = ThreadParticipant::query()
                ->where('thread_id', $item->id)
                ->where('user_id', '!=', wp_get_current_user()->ID)
                ->first()
                ->user_id;
            $alreadyExistDialogs[] = $dialogParticipant;
            $threadMessage = ThreadMessage::query()
                ->where('thread_id', $item->id)
                ->orderBy('created_at', 'desc')
                ->first();
            return [
                $item->id =>  [
                    'participant_id' =>
                        $dialogParticipant
                    ,
                    'created_at' => $item->created_at,
                    'last_message' => $threadMessage ?
                        $threadMessage->messagePresenter()
                        : ''
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
            ->mapWithKeys(function ($item) {
                $threadMessage = ThreadMessage::query()
                    ->where('thread_id', $item->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                return [
                    $item->id =>  [
                        'id' => $item->id,
                        'subject' => $item->subject,
                        'created_at' => $threadMessage ?
                            $threadMessage->created_at : $item->created_at,
                        'last_message' => $threadMessage ?
                            $threadMessage->messagePresenter()
                            : ''
                    ],
                ];
            });


        $threads = array_merge($publicThreads->toArray(), $personalThreads->toArray());

//        User::query()
//            ->where('id', '!=', wp_get_current_user()->ID)
//            ->whereNotIn('id', $alreadyExistDialogs)
//            ->get()
//            ->each(function ($user) use (&$threads){
//                $threads[] = ['user_id' => $user->ID];
//            });
        $users['current_user_id'] = wp_get_current_user()->ID;

        return view('front.chat', [
//            'currentUser' => json_encode($currentUser->toArray()),
            'threads' => json_encode($threads),
            'users' => json_encode($users),
        ]);
    }
}