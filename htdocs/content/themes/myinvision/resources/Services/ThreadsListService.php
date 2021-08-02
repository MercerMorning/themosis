<?php
namespace Theme\Services;


use Carbon\Carbon;
use Theme\Models\Thread;
use Theme\Models\ThreadMessage;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;

class ThreadsListService
{
    static public function  getWholeList()
    {
        $users = User::all();
        $users = $users->mapWithKeys(function ($user) {
            $user->first_name = get_user_meta( $user->ID, 'first_name', true );
            $user->last_name = get_user_meta( $user->ID, 'last_name', true );
            $user->ava = get_user_meta( $user->ID, 'avatar', true ) == '^ ""'
                ? get_user_meta( $user->ID, 'avatar', true ) :
                get_template_directory_uri() . '/assets/images/ava.png';
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
                ->first();
            $alreadyExistDialogs[] = $dialogParticipant->user_id;
            $threadMessage = ThreadMessage::query()
                ->where('thread_id', $item->id)
                ->orderBy('created_at', 'desc')
                ->first();
            return [
                $item->id =>  [
                    'participant_id' =>
                        $dialogParticipant->user_id
                    ,
                    'first_name' => get_user_meta( $dialogParticipant->user_id, 'first_name', true ),
                    'last_name' => get_user_meta( $dialogParticipant->user_id, 'last_name', true ),
                    'id' => $item->id,
                    'created_at' => $item->created_at,
                    'datetime' => Carbon::parse($item->created_at)->format('d.m.Y'),
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
                        'datetime' => Carbon::parse($threadMessage->created_at
                            ?? $item->created_at)->format('d.m.Y'),
                        'last_message' => $threadMessage ?
                            $threadMessage->messagePresenter()
                            : ''
                    ],
                ];
            });


        $threads = array_merge($publicThreads->toArray(), $personalThreads->toArray());

        User::query()
            ->where('id', '!=', wp_get_current_user()->ID)
            ->whereNotIn('id', $alreadyExistDialogs)
            ->get()
            ->each(function ($user) use (&$threads){
                $threads[] = [
                    'participant_id' => $user->ID,
                   'first_name' => get_user_meta( $user->ID, 'first_name', true ),
                    'last_name' => get_user_meta( $user->ID, 'last_name', true ),
                ];
            });
        $users['current_user_id'] = wp_get_current_user()->ID;

        return ['threads' => $threads, 'users' => $users];
    }

    public static function formateMessages($thread_id)
    {
        $threadMessages = Thread::find($thread_id)->messages();
        $threadMessages = $threadMessages->map(function ($item) {
            if ($item->is_file) {
                $item->body = file_get_contents($item->body);
            }
            $item->date = $item->created_at->format('d.m.Y');
            return $item;
        })->groupBy('date')
            ->map(function ($item) {
            $messages = $item->toArray();
            $formatedMessages = [];
            $messagesGroupKey = 0;
            $lastMessage = 0;
            for ($i = 0; $i < count($messages); $i++) {
                if ($i == 0) {
                    $lastMessage = $formatedMessages[$messagesGroupKey][] = $messages[$i];
                } else {
                    if ($lastMessage['user_id'] !=  $messages[$i]['user_id']) {
                        $messagesGroupKey++;
                    }
                    $lastMessage = $formatedMessages[$messagesGroupKey][] = $messages[$i];
//                    $formatedMessages[$messagesGroupKey][] = $messages[$i];
                }
            }
            return $formatedMessages;
        });
        return $threadMessages;
    }
}