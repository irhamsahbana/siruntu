@extends('App')

@php
    $hasAccessCreate = Auth::user()->hasAccess( sprintf('course-create') );
@endphp


@section('content-header', 'Detail Mata Kuliah')

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('course.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <x-row>
                            <x-in-select
                                :label="'Master Mata Kuliah'"
                                :placeholder="'Pilih Master Mata Kuliah'"
                                :col="6"
                                :name="'course_master_id'"
                                :required="true"></x-in-select>
                            <x-in-select
                                :label="'Semester'"
                                :placeholder="'Pilih Semester'"
                                :col="6"
                                :name="'semester_id'"
                                :required="true"></x-in-select>
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
    <input type="hidden" id="url-course-masters" value="{{ route('select2.course-masters') }}">
    <input type="hidden" id="url-categories" value="{{ route('select2.categories') }}">
    <input type="hidden" id="data-semester-id" value="{{ $data->semester_id }}">
    <input type="hidden" id="data-course-master-id" value="{{ $data->course_master_id }}">

    <script>
        $(function() {
            $('#course_master_id').select2({
                theme: 'bootstrap4',
                allowClear: true,
                placeholder: {
                    id: '',
                    text: 'Pilih Master Mata Kuliah'
                },
                ajax: {
                    url: $('#url-course-masters').val(),
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

            $('#semester_id').select2({
                theme: 'bootstrap4',
                allowClear: true,
                placeholder: {
                    id: '',
                    text: 'Pilih Semester'
                },
                ajax: {
                    url: $('#url-categories').val(),
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        let query = {
                            category: 'semester',
                            keyword: params.term
                        }

                        return query;
                    },
                    processResults: function (data) {
                        data = $.map(data.data, function (obj) {
                            return {
                                id: obj.id,
                                text: obj.label
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
                url: '/select2/master-mata-kuliah/' + $( '#data-course-master-id' ).val(),
            }).then(function (data) {
                var option = new Option([data.ref_no, data.name].join(" - "), data.id, true, true);
                $('#course_master_id').append(option).trigger('change');

                data = $.map(data, function (obj) {
                    return {
                        id: obj.id,
                        text: [obj.ref_no, obj.name].join(' - ')
                    };
                });

                $('#course_master_id').trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });

            // semester
            $.ajax({
                type: 'GET',
                url: '/select2/kategori/' + $( '#data-semester-id' ).val(),
            }).then(function (data) {
                var option = new Option(data.label, data.id, true, true);
                $('#semester_id').append(option).trigger('change');

                data = $.map(data, function (obj) {
                    return {
                        id: obj.id,
                        text: data.label
                    };
                });

                $('#semester_id').trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
            });
        });
    </script>
@endpush
