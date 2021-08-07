@extends('layouts.main')
@section('main_content')
        <div id="app">
                <chat-component
                        currentuser="{{ $currentUser }}"
                        currentthread="{{ $currentThread }}"
                        threadmessages ="{{ $threadMessages }}"
                        threads="{{ $threads }}"
                >
                </chat-component>
            </div>
@endsection