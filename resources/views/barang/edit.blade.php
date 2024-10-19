@extends('layouts.master')
@section('title') Barang Management @endsection

@section('content')
@component('common-components.breadcrumb')
@slot('pagetitle') Barang Management @endslot
@slot('title') Edit Barang @endslot
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
                            <h5 class="font-size-16 mb-1">Edit Barang</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk mengedit data barang.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="barcode" class="col-md-2 col-form-label">Barcode</label>
                            <div class="col-md-4">
                                <input type="text" id="barcode" name="barcode" class="form-control"
                                    placeholder="Masukkan barcode" value="{{ old('barcode', $barang->barcode) }}"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nama_barang" class="col-md-2 col-form-label">Nama Barang</label>
                            <div class="col-md-4">
                                <input type="text" id="nama_barang" name="nama_barang" class="form-control"
                                    placeholder="Masukkan nama barang"
                                    value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-8">
                                <input type="text" id="keterangan" name="keterangan" class="form-control"
                                    placeholder="Masukkan Keterangan"
                                    value="{{ old('keterangan', $barang->keterangan) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="satuan_per_dos" class="col-md-2 col-form-label">Satuan per Dos</label>
                            <div class="col-md-3">
                                <input type="number" id="satuan_per_dos" name="satuan_per_dos" class="form-control"
                                    placeholder="Masukkan satuan per dos"
                                    value="{{ old('satuan_per_dos', $barang->satuan_per_dos) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="stok_dos" class="col-md-2 col-form-label">Stok Dos</label>
                            <div class="col-md-3">
                                <input type="number" id="stok_dos" name="stok_dos" class="form-control"
                                    placeholder="Masukkan stok dos" value="{{ old('stok_dos', $barang->stok_dos) }}"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="stok_pcs" class="col-md-2 col-form-label">Stok Pcs</label>
                            <div class="col-md-3">
                                <input type="number" id="stok_pcs" name="stok_pcs" class="form-control"
                                    placeholder="Masukkan stok pcs" value="{{ old('stok_pcs', $barang->stok_pcs) }}"
                                    required>
                            </div>
                        </div>

                        <!-- Toggle switch untuk satuan lainnya -->
                        <div class="row mb-3">
                            <label for="is_default" class="col-md-2 col-form-label">Stok Lainnya</label>
                            <div class="col-md-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_default" name="is_default"
                                        value="1" {{ old('is_default', $barang->stok_lainnya) ? 'checked' : '' }}
                                        onclick="toggleSatuanInduk()">
                                    <label class="form-check-label" for="is_default">Pilih Satuan Lainnya</label>
                                </div>
                            </div>
                        </div>

                        <div id="satuanIndukSection" style="display: none;">
                            <div class="row mb-3">
                                <label for="stok_lainnya" class="col-md-2 col-form-label">Stok</label>
                                <div class="col-md-3">
                                    <input type="number" id="stok_lainnya" name="stok_lainnya" class="form-control"
                                        placeholder="Masukkan Stok Lainnya"
                                        value="{{ old('stok_lainnya', $barang->stok_lainnya) }}">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control form-select" id="satuan_lainnya" name="satuan_lainnya">
                                        <option value="">-Pilih Satuan-</option>
                                        @foreach($satuans as $satuan)
                                        <option value="{{ $satuan->nama_satuan }}" 
                                            {{ old('satuan_lainnya', $barang->satuan_lainnya) == $satuan->nama_satuan ? 'selected' : '' }}>
                                            {{ $satuan->nama_satuan }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="uil uil-save me-1"></i> Simpan</button>
                        <a href="{{ route('barang.index') }}" class="btn btn-warning">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSatuanInduk() {
        var isDefault = document.getElementById('is_default').checked;
        var satuanIndukSection = document.getElementById('satuanIndukSection');
        var stokLainnyaInput = document.getElementById('stok_lainnya');
        var satuanLainnyaSelect = document.getElementById('satuan_lainnya');

        if (isDefault) {
            satuanIndukSection.style.display = 'block';
            stokLainnyaInput.setAttribute('required', true);
            satuanLainnyaSelect.setAttribute('required', true);
        } else {
            satuanIndukSection.style.display = 'none';
            stokLainnyaInput.removeAttribute('required');
            satuanLainnyaSelect.removeAttribute('required');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleSatuanInduk();
    });
</script>

@endsection
