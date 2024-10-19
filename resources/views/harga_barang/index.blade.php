@extends('layouts.master')
@section('title') Harga Barang Management @endsection

@section('content')
@component('common-components.breadcrumb')
    @slot('pagetitle') Harga Barang Management @endslot
    @slot('title') Daftar Harga Barang @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <a href="{{ route('harga_barang.create') }}" class="btn btn-success btn-xs waves-effect waves-light">
                                <i class="mdi mdi-plus me-2"></i> Tambah Harga Barang
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-inline float-md-end mb-3">
                            <div class="search-box ms-2">
                                <div class="position-relative">
                                    <form action="{{ route('harga_barang.index') }}" method="GET">
                                        <input type="text" name="search" class="form-control rounded bg-light border-0"
                                            placeholder="Search..." value="{{ request('search') }}">
                                        <button type="submit" class="btn position-absolute top-0 end-0 bg-transparent border-0">
                                            <i class="mdi mdi-magnify search-icon"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Zona</th>
                                <th>Harga per Dos</th>
                                <th>Harga per Pcs</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hargaBarang as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->barang->nama_barang }}</td>
                                    <td>{{ $item->zona->nama }}</td>
                                    <td>{{ number_format($item->harga_per_dos, 2) }}</td>
                                    <td>{{ number_format($item->harga_per_pcs, 2) }}</td>
                                    <td>
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                <a href="{{ route('harga_barang.edit', $item->id_harga) }}" class="px-2 text-primary"><i
                                                        class="uil uil-pen font-size-18"></i></a>
                                            </li>
                                            <li class="list-inline-item">
                                                <form action="{{ route('harga_barang.destroy', $item->id_harga) }}" method="POST" class="form-delete" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" style="border:none; background:none; padding:0;" class="btn-delete">
                                                        <i class="uil uil-trash-alt font-size-18 text-danger"></i>
                                                    </button>
                                                </form>
                                            </li>                                      
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $hargaBarang->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
