@extends('App')

@php
    $hasAccessCreate = Auth::user()->hasAccess( sprintf('category-%s-create', $data->group_by) );
@endphp


@section('content-header', 'Detail Kategori')

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <form style="width: 100%" action="{{ route('category.update', $data->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="group_by" value="{{ $data->group_by }}">
                        <x-in-text
                            :label="'Nama'"
                            :placeholder="'Masukkan Nama Kategori'"
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
