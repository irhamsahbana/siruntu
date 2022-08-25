@extends('App')

@php
    $mode = 'standart';
    // $mode = 'whiteboard';
@endphp

@section('content-header', 'Kursus Langsung')

@section('content')
    @if($mode == 'standart')
        @include('pages.LiveCourseStandart')
    @endif

    @if($mode == 'whiteboard')
        @include('pages.LiveCourseWhiteboard')
    @endif

@endsection

@push('js')
    <input type="hidden" id="hostname" value="{{ env('APP_URL') }}">
    <input type="hidden" id="room" value="{{ \Request::segment(3) }}">
    <input type="hidden" id="person_category" value="{{ $profile->person->category->name }}">

    <script src="https://cdn.socket.io/4.4.1/socket.io.min.js" integrity="sha384-fKnu0iswBIqkjxrhQCTZ7qlLHOFEgNkRmK2vaO/LbTZSXdJfAu6ewRBdwHPhBo/H" crossorigin="anonymous"></script>
    <script src="{{ asset('assets') }}/workspace/socketClient.js?version={{ \Str::random(45) }}"></script>
    <script src="{{ asset('assets') }}/workspace/liveCourse.js?version={{ \Str::random(45) }}"></script>
    <script src="/bower_components/webrtc-adapter/release/adapter.js?version={{ \Str::random(45) }}"></script>
    <script src="/bower_components/kurento-utils/js/kurento-utils.js?version={{ \Str::random(45) }}"></script>
@endpush