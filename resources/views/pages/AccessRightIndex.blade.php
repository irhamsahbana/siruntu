@extends('App')

@section('content-header', 'Hak Akses')

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col class="mb-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Tambah</button>
                    </x-col>

                    <x-col>
                        <x-table :thead="['Hak Akses', 'Aksi']">
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->label }}</td>
                                    <td>
                                        <a
                                            href="{{ route('access-right.show', $row->id) }}"
                                            class="btn btn-warning"
                                            title="Ubah"><i class="fas fa-pencil-alt"></i></a>

                                        <form style=" display:inline!important;" method="POST" action="{{ route('access-right.destroy', $row->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                                title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                        </form>
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
        <form style="width: 100%" action="{{ route('access-right.store') }}" method="POST">
            @csrf
            @method('POST')

            <x-row>
                <x-in-text
                    :label="'Nama'"
                    :placeholder="'Masukkan Nama Hak Akses'"
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
