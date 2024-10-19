@extends('layouts.master')
@section('title') Barang Management @endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Barang Management @endslot
        @slot('title') Tambah Barang @endslot
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
                                <h5 class="font-size-16 mb-1">Tambah Barang</h5>
                                <p class="text-muted text-truncate mb-0">Form ini digunakan untuk menambah data barang.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('barang.store') }}" method="POST">
                            @csrf
    
                            <!-- Barcode -->
                            <div class="row mb-3">
                                <label for="barcode" class="col-md-2 col-form-label">Barcode</label>
                                <div class="col-md-4">
                                    <input type="text" id="barcode" name="barcode" class="form-control"
                                        placeholder="Masukkan barcode" value="{{ old('barcode') }}" required>
                                </div>
                            </div>
    
                            <!-- Nama Barang -->
                            <div class="row mb-3">
                                <label for="nama_barang" class="col-md-2 col-form-label">Nama Barang</label>
                                <div class="col-md-4">
                                    <input type="text" id="nama_barang" name="nama_barang" class="form-control"
                                        placeholder="Masukkan nama barang" value="{{ old('nama_barang') }}" required>
                                </div>
                            </div>
    
                            <!-- Keterangan -->
                            <div class="row mb-3">
                                <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                                <div class="col-md-8">
                                    <input type="text" id="keterangan" name="keterangan" class="form-control"
                                        placeholder="Masukkan Keterangan" value="{{ old('keterangan') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="satuan_per_dos" class="col-md-2 col-form-label">Satuan Per Dos</label>
                                <div class="col-md-3">
                                    <input type="number" id="satuan_per_dos" name="satuan_per_dos" class="form-control"
                                        placeholder="Masukkan Satuan Per Dos" value="{{ old('satuan_per_dos') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="stok_dos" class="col-md-2 col-form-label">Stok Dos</label>
                                <div class="col-md-3">
                                    <input type="number" id="stok_dos" name="stok_dos" class="form-control"
                                        placeholder="Masukkan Stok Dos" value="{{ old('stok_dos') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="stok_pcs" class="col-md-2 col-form-label">Stok Pcs</label>
                                <div class="col-md-3">
                                    <input type="number" id="stok_pcs" name="stok_pcs" class="form-control"
                                        placeholder="Masukkan Stok Pcs" value="{{ old('stok_pcs') }}" required>
                                </div>
                            </div>
    
                            <!-- Toggle switch untuk satuan terkecil -->
                            <div class="row mb-3">
                                <label for="is_default" class="col-md-2 col-form-label">Stok Lainnya</label>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="0" onclick="toggleSatuanInduk()">
                                        <label class="form-check-label" for="is_default">Pilih Satuan Lainnya</label>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Satuan Induk dan Faktor Konversi (Hanya tampil jika bukan satuan terkecil) -->
                            <div id="satuanIndukSection" style="display: none;">
                             <!-- Stok dan Satuan -->
                             <div class="row mb-3">
                                <label for="stok_lainnya" class="col-md-2 col-form-label">Stok</label>
                                <div class="col-md-3">
                                    <input type="number" id="stok_lainnya" name="stok_lainnya" class="form-control"
                                        placeholder="Masukkan Stok Lainnya" value="{{ old('stok_lainnya') }}" required>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control form-select" id="satuan_lainnya" name="satuan_lainnya">
                                        <option value="">-Pilih Satuan-</option>
                                        @foreach($satuans as $satuan)
                                            <option value="{{ $satuan->nama_satuan }}">{{ $satuan->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
    
                            <button type="submit" class="btn btn-primary"> <i class="uil uil-save me-1"></i> Simpan</button>
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
            var satuanPerDosInput = document.getElementById('satuan_per_dos');
            var stokDosInput = document.getElementById('stok_dos');
            var satuanLainnyaSelect = document.getElementById('satuan_lainnya');
            var stokLainnyaInput = document.getElementById('stok_lainnya');
    
            // Tampilkan atau sembunyikan section Satuan Induk dan Faktor Konversi
            if (isDefault) {
                satuanIndukSection.style.display = 'block'; // Tampilkan jika satuan lainnya
                satuanPerDosInput.value = 0;  // Set nilai menjadi 0
                stokDosInput.value = 0;       // Set nilai menjadi 0
                satuanPerDosInput.setAttribute('readonly', true); // Lock input
                stokDosInput.setAttribute('readonly', true); // Lock input
                satuanLainnyaSelect.setAttribute('required', true); // Buat required
                stokLainnyaInput.setAttribute('required', true); // Buat required
            } else {
                satuanIndukSection.style.display = 'none';  // Sembunyikan jika bukan satuan lainnya
                satuanPerDosInput.removeAttribute('readonly'); // Buka kunci input
                stokDosInput.removeAttribute('readonly'); // Buka kunci input
                satuanLainnyaSelect.removeAttribute('required'); // Hilangkan required
                stokLainnyaInput.removeAttribute('required'); // Hilangkan required
            }
        }
    
        // Saat pertama kali halaman dimuat, jalankan fungsi toggle
        document.addEventListener('DOMContentLoaded', function () {
            toggleSatuanInduk();
        });
    </script>
    
    
@endsection
