@extends('App')

@php
    $hasAccessCreate = Auth::user()->hasAccess( sprintf('classroom-create') );
@endphp


@section('content-header', 'Detail Ruang Kelas')

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('classroom.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <x-row>
                            <x-in-select
                                :label="'Mata Kuliah'"
                                :placeholder="'Pilih Mata Kuliah'"
                                :col="6"
                                :name="'course_id'"
                                :required="true"></x-in-select>
                                <x-in-text
                                :label="'Nama'"
                                :placeholder="'Masukkan Nama'"
                                :col="6"
                                :name="'name'"
                                :value="$data->name"
                                :required="true"></x-in-text>
                        </x-row>

                        <x-col class="text-right">
                            @if($hasAccessCreate)
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            @endif
                        </x-col>
                    </form>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection

@push('js')
    <input type="hidden" id="url-courses" value="{{ route('select2.courses') }}">
    <input type="hidden" id="data-course-id" value="{{ $data->course_id }}">

    <script>
        $(function() {
            $('#course_id').select2({
                theme: 'bootstrap4',
                allowClear: true,
                placeholder: {
                    id: '',
                    text: 'Pilih Mata Kuliah'
                },
                ajax: {
                    url: $('#url-courses').val(),
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        let query = {
                            keyword: params.term
                        }

                        return query;
                    },
                    processResults: function (data) {
                        data = $.map(data.data, function (obj) {
                            return {
                                id: obj.id,
                                text: [obj.ref_no, obj.name].join(' - ')
                            };
                        });

                        return {
                            results: data
                        };
                    },
                    cache: false
                }
            });

            //fetch data from server to fill select2

            // course master
            $.ajax({
                type: 'GET',
                url: '/select2/mata-kuliah/' + $( '#data-course-id' ).val(),
            }).then(function (data) {
                var option = new Option([data.ref_no, data.name].join(" - "), data.id, true, true);
                $('#course_id').append(option).trigger('change');

                data = $.map(data, function (obj) {
                    return {
                        id: obj.id,
                        text: [obj.ref_no, obj.name].join(' - ')
                    };
                });

                $('#course_id').trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });
        });
    </script>
@endpush
