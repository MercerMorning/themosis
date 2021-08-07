@extends('layouts.main')
@section('main_content')
        <div id="app">
                <chat-component
                        usertoken="{{ $userToken }}"
                        currentuser="{{ $currentUser }}"
                        currentthread="{{ $currentThread }}"
                        threadmessages ="{{ $threadMessages }}"
                        threads="{{ $threads }}"
                >
                </chat-component>
            </div>
@endsection