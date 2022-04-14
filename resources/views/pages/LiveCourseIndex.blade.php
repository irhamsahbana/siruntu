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
    <script>
        $(document).ready(function () {
            $('#btn-change-mode').on('click', function () {
                alert('tombol ubah mode diklik');
            });
        });
    </script>
@endpush