<?php
namespace Theme\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\True_;
use Theme\Models\Thread;
use Theme\Models\ThreadParticipant;
use Theme\Models\User;
use Theme\Services\ThreadsListService;

class ThreadController extends Controller
{
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
//            'name' => 'required_without:participant_id|string',
//            'participant_id' => 'required_without:name|string',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }
//        return 123;
        $threadParameters = ['subject' => $request->get('name')];
        if ($request->has('participant_id')) {
            $threadParameters['private'] = 1;
        } else {
            $threadParameters['private'] = 0;
        }

        $thread = Thread::create($threadParameters);

        $isSuccess = ThreadParticipant::create(['thread_id' => $thread->id,
            'user_id' => wp_get_current_user()->ID
        ]);

        if ($request->has('participant_id')) {
            ThreadParticipant::create(['thread_id' => $thread->id,
                'user_id' => $request->get('participant_id')
            ]);
        }

//        if ($request->has('participants_id')) {
//            foreach ($request->get('p'))
//            ThreadParticipant::create(['thread_id' => $thread->id,
//                'user_id' => $request->get('participant_id')
//            ]);
//        }


        $threads = ThreadsListService::getWholeList()['threads'];
        $threads['new_thread_id'] = $thread->id;
        if ($isSuccess) {
            return response($threads);
        }
        return response('', 400);
    }

    public function inviteParticipant(Request $request)
    {
//        return $request->all();
        $validator = Validator::make($request->all(), [
            'thread_id' => 'required|exists:threads,id',
            'participants_id' => 'required|exists:users,ID',
        ]);

        if ($validator->fails()) {
            return response('', 400);
        }

        foreach ($request->get('participants_id') as $participant) {
            ThreadParticipant::create(['thread_id' => $request->get('thread_id'),
                'user_id' => $participant
            ]);
        }

    }

    public function getThreadMessages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thread_id' => 'required|exists:threads,id',
        ]);

        if ($validator->fails()) {
            return response('', 400);
        }

        $threadMessages = Thread::find($request->get('thread_id'))->messages();
        $threadMessages = $threadMessages->map(function ($item) {
            $item->date = $item->created_at->format('d.m.Y');
            return $item;
        })->groupBy('date');


        if ($threadMessages) {
            return response($threadMessages);
        }
         return response('', 400);
    }

    function getAll()
    {
        $threads = ThreadsListService::getWholeList()['threads'];
        return response($threads);
    }
}