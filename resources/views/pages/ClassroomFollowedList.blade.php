@extends('App')

@section('content-header', 'Kelas yang Diikuti')

@section('content')
    <x-content>
        <x-row>
            @foreach ($data as $row)
                <x-card-collapsible :title="$row->course_name .' - '. $row->name" col="4" :collapse="false">
                    <x-row>
                        <x-col class="text-right">
                            <a href="{{ route('classroom.live-course', $row->id) }}" class="btn btn-success">Kelas langsung</a>
                            <button type="submit" class="btn btn-success">Masuk</button>
                        </x-col>
                    </x-row>
                </x-card-collapsible>
            @endforeach

            <x-col class="d-flex justify-content-center">
                {{ $data->links() }}
            </x-col>
        </x-row>
    </x-content>
@endsection