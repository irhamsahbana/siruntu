@extends('App')

@php
    $categories = [
        ['group_by' => 'semester', 'label' => 'Semester', 'icon' => 'fas fa-newspaper'],
    ];

    $no = 1;
@endphp

@section('content-header', 'Daftar Kategori')

@section('content')
    <x-content>
        <x-row>
            <x-card-collapsible>
                <x-row>
                    <x-col>
                        <x-table :thead="['Kategori']">
                            @foreach($categories as $data)
                                @if(Auth::user()->hasAccess( sprintf('category-%s-read', $data['group_by']) ))
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <a
                                                href="{{ route('category.index', ['category' => $data['group_by']]) }}">
                                                <i class="{{ $data['icon'] }}"></i> {{ $data['label'] }}
                                                </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </x-table>
                    </x-col>
                </x-row>
            </x-card-collapsible>
        </x-row>
    </x-content>
@endsection
