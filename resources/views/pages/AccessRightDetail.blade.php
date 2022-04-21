@extends('App')

@section('content-header', 'Detail Hak Akses')

@php
    $dataPermissions = $permissions->pluck('id')->toArray();
@endphp

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('access-right.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <x-in-text
                            :label="'Nama'"
                            :placeholder="'Masukkan Nama Hak Akses'"
                            :col="12"
                            :name="'label'"
                            :value="$data->label"
                            :required="true"></x-in-text>
                        <x-in-text
                            :label="'Catatan'"
                            :placeholder="'Masukkan Catatan'"
                            :col="12"
                            :name="'notes'"
                            :value="$data->notes"
                            :required="true"></x-in-text>

                        <x-group :label="'Permission'">
                            @foreach ($permissions as $permission)
                                <x-in-checkbox
                                    :label="sprintf('%s | %s', $permission->notes, $permission->label)"
                                    :name="'permission_ids[]'"
                                    :value="$permission->id"
                                    :isChecked="$groupPermissions->contains($permission->id)"></x-in-checkbox>
                            @endforeach
                        </x-group>

                        <x-col class="text-right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </x-col>
                    </form>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection
