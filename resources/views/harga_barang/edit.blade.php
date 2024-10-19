@extends('layouts.master')
@section('title') Edit Harga Barang @endsection

@section('content')
@component('common-components.breadcrumb')
    @slot('pagetitle') Harga Barang Management @endslot
    @slot('title') Edit Harga Barang @endslot
@endcomponent

<div class="row">
    <div class="col-xl-12">
        <div class="custom-accordion">
            <div class="card">
                <div class="p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <i class="uil uil-receipt text-primary h2"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="font-size-16 mb-1">Edit Harga Barang</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk mengedit data harga barang.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('harga_barang.update', $hargaBarang->id_harga) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="barang_id" class="col-md-2 col-form-label">Barang</label>
                            <div class="col-md-4">
                                <select id="barang_id" name="barang_id" class="form-control form-select" required>
                                    @foreach ($barang as $item)
                                        <option value="{{ $item->id_barang }}" {{ $item->id_barang == $hargaBarang->barang_id ? 'selected' : '' }}>
                                            {{ $item->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="zona_id" class="col-md-2 col-form-label">Zona</label>
                            <div class="col-md-4">
                                <select id="zona_id" name="zona_id" class="form-control form-select" required>
                                    @foreach ($zona as $item)
                                        <option value="{{ $item->id_zona }}" {{ $item->id_zona == $hargaBarang->zona_id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="harga_per_dos" class="col-md-2 col-form-label">Harga per Dos</label>
                            <div class="col-md-4">
                                <input type="number" id="harga_per_dos" name="harga_per_dos" class="form-control" placeholder="Masukkan harga per dos"
                                    value="{{ old('harga_per_dos', $hargaBarang->harga_per_dos) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="harga_per_pcs" class="col-md-2 col-form-label">Harga per Pcs</label>
                            <div class="col-md-4">
                                <input type="number" id="harga_per_pcs" name="harga_per_pcs" class="form-control" placeholder="Masukkan harga per pcs"
                                    value="{{ old('harga_per_pcs', $hargaBarang->harga_per_pcs) }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary"> <i class="uil uil-save me-1"></i> Simpan</button>
                        <a href="{{ route('harga_barang.index') }}" class="btn btn-warning">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
