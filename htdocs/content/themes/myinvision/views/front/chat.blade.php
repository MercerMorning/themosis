@extends('layouts.main')
@section('main_content')
        <div id="chat-app" class="chat-wrapper">
                <chat-component
                        usertoken="{{ $userToken }}"
                        currentuser="{{ $currentUser }}"
                        currentthread="{{ $currentThread }}"
                        threadmessages ="{{ $threadMessages }}"
                        threads="{{ $threads }}"
                        users="{{ $users }}"
                >
                </chat-component>
            </div>
@endsection