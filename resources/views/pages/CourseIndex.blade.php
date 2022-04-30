@extends('App')

@php
    $hasAccessCreate = Auth::user()->hasAccess('course-create');
    $hasAccessRead = Auth::user()->hasAccess('course-read');
    // $hasAccessUpdate = Auth::user()->hasAccess('course-update');
    $hasAccessDelete = Auth::user()->hasAccess('course-delete');
@endphp

@section('content-header', 'Mata Kuliah')

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        @if($hasAccessCreate)
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                        @endif
                    </x-col>

                    <x-col>
                        <x-table :thead="['Kode', 'Nama', 'Semester', 'Aksi']">
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->ref_no }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->semester_label }}</td>
                                    <td>
                                        @if($hasAccessRead)
                                            <a
                                                href="{{ route('classroom.index', ['course_id' => $row->id]) }}"
                                                class="btn btn-primary"
                                                title="Ruang Kelas"><i class="fas fa-chalkboard"></i></a>
                                            <a
                                                href="{{ route('course.show', $row->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                        @endif

                                        @if($hasAccessDelete)
                                            <form style=" display:inline!important;" method="POST" action="{{ route('course.destroy', $row->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                    title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    </x-col>

                    <x-col class="d-flex justify-content-end">
                        {{ $data->links() }}
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>

    <x-modal :title="'Tambah Data'" :id="'add-modal'" :size="'xl'">
        <form style="width: 100%" action="{{ route('course.store') }}" method="POST">
            @csrf
            @method('POST')

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
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </x-col>
        </form>
    </x-modal>
@endsection

@push('js')
    <input type="hidden" id="url-course-masters" value="{{ route('select2.course-masters') }}">
    <input type="hidden" id="url-categories" value="{{ route('select2.categories') }}">

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
                            category: 'semesters',
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
        });
    </script>
@endpush