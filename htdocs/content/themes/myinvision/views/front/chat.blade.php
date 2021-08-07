@extends('layouts.main')
@section('main_content')
        <div id="app">
                <chat-component
                        currentuser="{{ $currentUser }}"
                        threads="{{ $threads }}"
                >
                </chat-component>
            </div>
@endsection