@extends('layouts.master')
@section('title') Edit Barang Masuk @endsection
@section('css')
    <!-- DataTables -->
    <style>
        .item-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .item-row > div {
            flex: 1;
            margin-right: 10px;
        }
        .remove-item {
            color: red;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
@endsection
@section('content')
@component('common-components.breadcrumb')
    @slot('pagetitle') Barang Masuk Management @endslot
    @slot('title') Edit Barang Masuk @endslot
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
                            <h5 class="font-size-16 mb-1">Edit Barang Masuk</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk mengedit data barang masuk.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Form untuk edit data barang masuk -->
                    <form action="{{ route('barangmasuk.update', $barangMasuk->id_masuk) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Kode Barang Masuk -->
                        <div class="mb-3 row">
                            <label for="kode_barang_masuk" class="col-md-2 col-form-label">Kode Barang Masuk</label>
                            <div class="col-md-4">
                                <input type="text" id="kode_barang_masuk" name="kode_barang_masuk" class="form-control" value="{{ $barangMasuk->kode_barang_masuk }}" readonly>
                            </div>
                        </div>

                        <!-- Tanggal Masuk -->
                        <div class="mb-3 row">
                            <label for="tanggal_masuk" class="col-md-2 col-form-label">Tanggal Masuk</label>
                            <div class="col-md-4">
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control" value="{{ $barangMasuk->tanggal_masuk }}" required>
                            </div>
                        </div>

                        <!-- Depo -->
                        <div class="mb-3 row">
                            <label for="id_depo" class="col-md-2 col-form-label">Depo</label>
                            <div class="col-md-4">
                                <select id="id_depo" name="id_depo" class="form-select" required>
                                    <option value="">Pilih Depo</option>
                                    @foreach($depos as $depo)
                                        <option value="{{ $depo->id_depo }}" {{ $barangMasuk->id_depo == $depo->id_depo ? 'selected' : '' }}>{{ $depo->nama_depo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3 row">
                            <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-10">
                                <textarea id="keterangan" name="keterangan" class="form-control" placeholder="Masukkan keterangan barang masuk">{{ $barangMasuk->keterangan }}</textarea>
                            </div>
                        </div>
                        <hr>
                        <button type="button" class="btn btn-success btn-sm mb-3" id="addItemButton"><i class="uil uil-plus"></i> Tambah Barang</button>
                        <!-- Daftar Barang -->
                        <div id="barang-list">
                            @foreach($barangMasuk->detailBarangMasuk as $index => $detail)
                                <div class="item-row">
                                    <div>
                                        <label for="barang_{{ $index + 1 }}">Nama Barang</label>
                                        <select id="barang_{{ $index + 1 }}" name="barang[]" class="form-select" required>
                                            <option value="">Pilih Barang</option>
                                            @foreach($barangs as $barang)
                                                <option value="{{ $barang->id_barang }}" {{ $detail->id_barang == $barang->id_barang ? 'selected' : '' }}>{{ $barang->nama_barang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="jumlah_dos_{{ $index + 1 }}">Jumlah per Dos</label>
                                        <input type="number" id="jumlah_dos_{{ $index + 1 }}" name="jumlah_dos[]" class="form-control" value="{{ $detail->jumlah_dos }}" placeholder="Jumlah per Dos" required>
                                    </div>
                                    <div>
                                        <label for="jumlah_pcs_{{ $index + 1 }}">Jumlah per Pcs</label>
                                        <input type="number" id="jumlah_pcs_{{ $index + 1 }}" name="jumlah_pcs[]" class="form-control" value="{{ $detail->jumlah_pcs }}" placeholder="Jumlah per Pcs" required>
                                    </div>
                                    <i class="remove-item fa fa-trash" style="cursor: pointer;" onclick="removeItem(this)"></i>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-4 offset-md-0">
                                <button type="submit" class="btn btn-primary"><i class="uil uil-save me-1"></i> Simpan</button>
                                <a href="{{ route('barangmasuk.index') }}" class="btn btn-warning">Kembali</a>
                            </div>
                        </div>
                    </form>
                    <!-- End of Form -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
    const barangs = @json($barangs); // Mengirim data barang dari server ke client-side

    document.addEventListener('DOMContentLoaded', function() {
        let itemCount = {{ count($barangMasuk->detailBarangMasuk) }};

        // Function to add a new item row
        function addItem() {
            itemCount++;
            const itemRow = document.createElement('div');
            itemRow.classList.add('item-row');
            let barangOptions = '<option value="">Pilih Barang</option>';
            barangs.forEach(barang => {
                barangOptions += `<option value="${barang.id_barang}">${barang.nama_barang}</option>`;
            });

            itemRow.innerHTML = `
                <div>
                    <label for="barang_${itemCount}">Nama Barang</label>
                    <select id="barang_${itemCount}" name="barang[]" class="form-select" required>
                        ${barangOptions}
                    </select>
                </div>
                <div>
                    <label for="jumlah_dos_${itemCount}">Jumlah per Dos</label>
                    <input type="number" id="jumlah_dos_${itemCount}" name="jumlah_dos[]" class="form-control" placeholder="Jumlah per Dos" required>
                </div>
                <div>
                    <label for="jumlah_pcs_${itemCount}">Jumlah per Pcs</label>
                    <input type="number" id="jumlah_pcs_${itemCount}" name="jumlah_pcs[]" class="form-control" placeholder="Jumlah per Pcs" required>
                </div>
                <i class="remove-item fa fa-trash" style="cursor: pointer;" onclick="removeItem(this)"></i>
            `;
            document.getElementById('barang-list').appendChild(itemRow);
        }

        // Function to remove an item row
        window.removeItem = function(element) {
            element.parentElement.remove();
        }

        // Bind addItem function to the button click event
        document.getElementById('addItemButton').addEventListener('click', addItem);
    });
</script>
@endsection
