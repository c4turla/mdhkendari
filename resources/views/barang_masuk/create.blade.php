@extends('layouts.master')
@section('title') Barang Masuk Management @endsection
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
    @slot('title') Tambah Barang Masuk @endslot
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
                            <h5 class="font-size-16 mb-1">Tambah Barang Masuk</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk menambah data barang masuk.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('barangmasuk.store') }}" method="POST">
                        @csrf

                        <div class="mb-3 row">
                            <label for="kode_barang_masuk" class="col-md-2 col-form-label">Kode Barang Masuk</label>
                            <div class="col-md-4">
                                <input type="text" id="kode_barang_masuk" name="kode_barang_masuk" class="form-control"
                                    value="{{ $kode }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="tanggal_masuk" class="col-md-2 col-form-label">Tanggal Masuk</label>
                            <div class="col-md-4">
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="id_depo" class="col-md-2 col-form-label">Depo</label>
                            <div class="col-md-4">
                                <select id="id_depo" name="id_depo" class="form-select" required>
                                    <option value="">Pilih Depo</option>
                                    @foreach($depos as $depo)
                                        <option value="{{ $depo->id_depo }}">{{ $depo->nama_depo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-10">
                                <textarea id="keterangan" name="keterangan" class="form-control"
                                    placeholder="Masukkan keterangan barang masuk"></textarea>
                            </div>
                        </div>

                        <hr>
                        <button type="button" id="add-item-button" class="btn btn-success btn-sm mb-3">
                            <i class="uil uil-plus"></i> Tambah Barang
                        </button>

                        <div id="barang-list">
                            <!-- Contoh Row Barang Pertama -->
                            <div class="item-row">
                                <div>
                                    <label for="barang_1">Nama Barang</label>
                                    <select id="barang_1" name="barang[]" class="form-select" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id_barang }}">{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="jumlah_dos_1">Jumlah per Dos</label>
                                    <input type="number" id="jumlah_dos_1" name="jumlah_dos[]" class="form-control"
                                        placeholder="Jumlah per Dos" required>
                                </div>
                                <div>
                                    <label for="jumlah_pcs_1">Jumlah per Pcs</label>
                                    <input type="number" id="jumlah_pcs_1" name="jumlah_pcs[]" class="form-control"
                                        placeholder="Jumlah per Pcs" required>
                                </div>
                                <div>
                                    <label for="jumlah_lainnya_1">Jumlah Lainnya</label>
                                    <input type="number" id="jumlah_lainnya_1" name="jumlah_lainnya[]"
                                        class="form-control" placeholder="Jumlah Lainnya">
                                </div>
                                <div>
                                    <label for="satuan_lainnya_1">Satuan</label>
                                    <select id="satuan_lainnya_1" name="satuan_lainnya[]" class="form-select">
                                        <option value="">Pilih Satuan</option>
                                        @foreach($satuans as $satuan)
                                            <option value="{{ $satuan->nama_satuan }}">{{ $satuan->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <i class="remove-item fa fa-trash" style="cursor: pointer;" onclick="removeItem(this)"></i>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 offset-md-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="uil uil-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('barangmasuk.index') }}" class="btn btn-warning">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    const barangs = @json($barangs);
    const satuans = @json($satuans);

    document.addEventListener('DOMContentLoaded', function () {
        let itemCount = 1;

        // Function to add a new item row
        function addItem() {
            itemCount++;
            const itemRow = document.createElement('div');
            itemRow.classList.add('item-row');

            let barangOptions = '<option value="">Pilih Barang</option>';
            barangs.forEach(barang => {
                barangOptions += `<option value="${barang.id_barang}">${barang.nama_barang}</option>`;
            });

            let satuanOptions = '<option value="">Pilih Satuan</option>';
            satuans.forEach(satuan => {
                satuanOptions += `<option value="${satuan.nama_satuan}">${satuan.nama_satuan}</option>`;
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
                    <input type="number" id="jumlah_dos_${itemCount}" name="jumlah_dos[]" 
                           class="form-control" placeholder="Jumlah per Dos" required>
                </div>
                <div>
                    <label for="jumlah_pcs_${itemCount}">Jumlah per Pcs</label>
                    <input type="number" id="jumlah_pcs_${itemCount}" name="jumlah_pcs[]" 
                           class="form-control" placeholder="Jumlah per Pcs" required>
                </div>
                <div>
                    <label for="jumlah_lainnya_${itemCount}">Jumlah Lainnya</label>
                    <input type="number" id="jumlah_lainnya_${itemCount}" name="jumlah_lainnya[]" 
                           class="form-control" placeholder="Jumlah Lainnya">
                </div>
                <div>
                    <label for="satuan_lainnya_${itemCount}">Satuan</label>
                    <select id="satuan_lainnya_${itemCount}" name="satuan_lainnya[]" 
                            class="form-select">${satuanOptions}</select>
                </div>
                <i class="remove-item fa fa-trash" style="cursor: pointer;" onclick="removeItem(this)"></i>
            `;

            document.getElementById('barang-list').appendChild(itemRow);
        }

        window.removeItem = function (element) {
            element.parentElement.remove();
        };

        document.getElementById('add-item-button').addEventListener('click', addItem);
    });
</script>
@endsection
