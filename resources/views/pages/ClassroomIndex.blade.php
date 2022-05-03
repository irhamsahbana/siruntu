@extends('App')

@php
    $hasAccessCreate = Auth::user()->hasAccess('classroom-create');
    $hasAccessRead = Auth::user()->hasAccess('classroom-read');
    // $hasAccessUpdate = Auth::user()->hasAccess('classroom-update');
    $hasAccessDelete = Auth::user()->hasAccess('classroom-delete');
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
                        <x-table :thead="['Mata Kuliah', 'Nama', 'Aksi']">
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ sprintf('%s - %s', $row->course_ref_no, $row->course_name) }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>
                                        @if($hasAccessRead)
                                            <a
                                                href="{{ route('classroom.show', $row->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                        @endif

                                        @if($hasAccessDelete)
                                            <form style=" display:inline!important;" method="POST" action="{{ route('classroom.destroy', $row->id) }}">
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
        <form style="width: 100%" action="{{ route('classroom.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-select
                    :label="'Mata Kuliah'"
                    :placeholder="'Pilih Master Mata Kuliah'"
                    :col="6"
                    :name="'course_id'"
                    :required="true"></x-in-select>
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkan Nama'"
                    :col="6"
                    :name="'name'"
                    :required="true"></x-in-text>
            </x-row>

            <x-col class="text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </x-col>
        </form>
    </x-modal>
@endsection

@push('js')
    <input type="hidden" id="url-courses" value="{{ route('select2.courses') }}">

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
        });
    </script>
@endpush