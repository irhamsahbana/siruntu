@extends('App')

@php
    $categoryName = '';
    $hasAccessCreate = false;
    $hasAccessRead = false;
    $hasAccessUpdate = false;
    $hasAccessDelete = false;

    $categories = [
        ['group_by' => 'semester', 'label' => 'Semester', 'icon' => 'fas fa-newspaper'],
    ];

    foreach ($categories as $category) {
        if ($category['group_by'] == app('request')->input('category')) {
            $categoryName = $category['label'];

            $hasAccessCreate = Auth::user()->hasAccess( sprintf('category-%s-create', $category['group_by']) );
            $hasAccessRead = Auth::user()->hasAccess( sprintf('category-%s-read', $category['group_by']) );
            // $hasAccessUpdate = Auth::user()->hasAccess( sprintf('category-%s-update', $category['group_by']) );
            $hasAccessDelete = Auth::user()->hasAccess( sprintf('category-%s-delete', $category['group_by']) );
            break;
        }
    }
@endphp

@section('content-header', 'Kategori - ' . $categoryName)

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
                        <x-table :thead="['Nama', 'Catatan', 'Aksi']">
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->label }}</td>
                                    <td>{{ $row->notes }}</td>
                                    <td>
                                        @if($hasAccessRead)
                                            <a
                                                href="{{ route('category.show', $row->id) }}"
                                                class="btn btn-warning"
                                                title="Ubah"><i class="fas fa-pencil-alt"></i></a>
                                        @endif

                                        @if($hasAccessDelete)
                                            <form style=" display:inline!important;" method="POST" action="{{ route('category.destroy', $row->id) }}">
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

    <x-modal :title="'Tambah Data'" :id="'add-modal'" :size="'lg'">
        <form style="width: 100%" action="{{ route('category.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <input type="hidden" name="group_by" value="{{ app('request')->input('category') }}">
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkan Nama kategori'"
                    :col="6"
                    :name="'label'"
                    :required="true">
                </x-in-text>
                <x-in-text
                    :label="'Catatan'"
                    :placeholder="'Masukkan Catatan'"
                    :col="6"
                    :name="'notes'"
                    :required="true">
                </x-in-text>
            </x-row>

            <x-col class="text-right">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </x-col>
        </form>
    </x-modal>

@endsection
