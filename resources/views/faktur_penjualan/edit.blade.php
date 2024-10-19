@extends('layouts.master')
@section('title') Edit Faktur Penjualan @endsection
@section('css')
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
    @slot('pagetitle') Faktur Penjualan Management @endslot
    @slot('title') Edit Faktur Penjualan @endslot
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
                            <h5 class="font-size-16 mb-1">Edit Faktur Penjualan</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk mengedit data faktur penjualan.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Form untuk input data faktur penjualan -->
                    <form action="{{ route('faktur_penjualan.update', $faktur->id_faktur) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Nomor Bukti -->
                        <div class="mb-3 row">
                            <label for="nomor_bukti" class="col-md-2 col-form-label">Nomor Faktur</label>
                            <div class="col-md-4">
                                <input type="text" id="nomor_bukti" name="nomor_bukti" class="form-control" value="{{ $faktur->nomor_bukti }}" readonly>
                            </div>
                        </div>

                        <!-- Outlet -->
                        <div class="mb-3 row">
                            <label for="id_outlet" class="col-md-2 col-form-label">Outlet</label>
                            <div class="col-md-4">
                                <select id="id_outlet" name="id_outlet" class="form-select" required>
                                    <option value="">Pilih Outlet</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id_outlet }}" {{ $faktur->id_outlet == $outlet->id_outlet ? 'selected' : '' }}>{{ $outlet->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Buat -->
                        <div class="mb-3 row">
                            <label for="tanggal_buat" class="col-md-2 col-form-label">Tanggal Buat</label>
                            <div class="col-md-2">
                                <input type="date" id="tanggal_buat" name="tanggal_buat" class="form-control" value="{{ $faktur->tanggal_buat }}" required>
                            </div>
                        </div>

                        <!-- Tanggal Jatuh Tempo -->
                        <div class="mb-3 row">
                            <label for="tanggal_jatuh_tempo" class="col-md-2 col-form-label">Tanggal Jatuh Tempo</label>
                            <div class="col-md-2">
                                <input type="date" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" class="form-control" value="{{ $faktur->tanggal_jatuh_tempo }}">
                            </div>
                        </div>

                        <!-- Cara Pembayaran -->
                        <div class="mb-3 row">
                            <label for="cara_pembayaran" class="col-md-2 col-form-label">Cara Pembayaran</label>
                            <div class="col-md-4">
                                <select id="cara_pembayaran" name="cara_pembayaran" class="form-select" required>
                                    <option value="CASH" {{ $faktur->cara_pembayaran == 'CASH' ? 'selected' : '' }}>CASH</option>
                                    <option value="CREDIT" {{ $faktur->cara_pembayaran == 'CREDIT' ? 'selected' : '' }}>CREDIT</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <button type="button" onclick="addItem()" class="btn btn-success btn-sm mb-3"><i class="uil uil-plus"></i> Tambah Barang</button>

                        <!-- Daftar Barang -->
                        <div id="barang-list">
                     
                            @foreach($faktur->detailFakturPenjualan as $index => $detail)
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
                                    <label for="jumlah_dos_{{ $index + 1 }}">Jumlah Dos</label>
                                    <input type="number" id="jumlah_dos_{{ $index + 1 }}" name="jumlah_dos[]" class="form-control" value="{{ $detail->jumlah_dos }}" oninput="calculateTotal(this)" min="0">
                                </div>
                                <div>
                                    <label for="jumlah_pcs_{{ $index + 1 }}">Jumlah Pcs</label>
                                    <input type="number" id="jumlah_pcs_{{ $index + 1 }}" name="jumlah_pcs[]" class="form-control" value="{{ $detail->jumlah_pcs }}" oninput="calculateTotal(this)" min="0">
                                </div>
                                <div>
                                    <label for="harga_{{ $index + 1 }}">Harga</label>
                                    <input type="number" id="harga_{{ $index + 1 }}" name="harga[]" class="form-control" value="{{ $detail->harga }}" required>
                                </div>
                                <div>
                                    <label for="diskon_{{ $index + 1 }}">Diskon</label>
                                    <input type="number" id="diskon_{{ $index + 1 }}" name="diskon[]" class="form-control" value="{{ $detail->diskon }}" oninput="calculateTotal(this)">
                                </div>
                                <div>
                                    <label for="total_{{ $index + 1 }}">Total</label>
                                    <input type="number" id="total_{{ $index + 1 }}" name="total[]" class="form-control" value="{{ $detail->total_harga }}" readonly>
                                </div>
                                <i class="remove-item fa fa-trash" style="cursor: pointer;" onclick="removeItem(this)"></i>
                            </div>
                            @endforeach
                        </div>

                        <!-- Grand Total -->
                        <div class="mb-3 row justify-content-end">
                            <label for="grand_total" class="col-md-1 col-form-label">Grand Total</label>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="number" id="grand_total" name="grand_total" class="form-control" value="{{ $faktur->grand_total }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-4 offset-md-0">
                                <button type="submit" class="btn btn-primary"><i class="uil uil-save me-1"></i> Simpan</button>
                                <a href="{{ route('faktur_penjualan.index') }}" class="btn btn-warning">Kembali</a>
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
    let itemCount = {{ count($faktur->detailFakturPenjualan) }};

    // Function to add a new item row
    function addItem() {
        itemCount++;
        const itemRow = document.createElement('div');
        itemRow.classList.add('item-row');
        
        // Creating dropdown list for barang
        let barangOptions = '<option value="">Pilih Barang</option>';
        @foreach($barangs as $barang)
            barangOptions += `<option value="{{ $barang->id_barang }}">{{ $barang->nama_barang }}</option>`;
        @endforeach

        itemRow.innerHTML = `
            <div> 
                <select id="barang_${itemCount}" name="barang[]" class="form-select" required>
                    ${barangOptions}
                </select>
            </div>
            <div>
                <input type="number" id="jumlah_dos_${itemCount}" name="jumlah_dos[]" class="form-control" oninput="calculateTotal(this)" min="0">
            </div>
            <div>
                <input type="number" id="jumlah_pcs_${itemCount}" name="jumlah_pcs[]" class="form-control" oninput="calculateTotal(this)" min="0">
            </div>
            <div>
                <input type="number" id="harga_${itemCount}" name="harga[]" class="form-control" required>
            </div>
            <div>
                <input type="number" id="diskon_${itemCount}" name="diskon[]" class="form-control" oninput="calculateTotal(this)">
            </div>
            <div>
                <input type="number" id="total_${itemCount}" name="total[]" class="form-control" readonly>
            </div>
            <i class="remove-item fa fa-trash" style="cursor: pointer;" onclick="removeItem(this)"></i>
        `;
        document.getElementById('barang-list').appendChild(itemRow);
    }

    // Function to remove an item row
    function removeItem(element) {
        element.closest('.item-row').remove();
        calculateGrandTotal();
    }

    // Function to calculate total price per item
    function calculateTotal(element) {
        const row = element.closest('.item-row');
        const harga = row.querySelector('input[name="harga[]"]').value || 0;
        const diskon = row.querySelector('input[name="diskon[]"]').value || 0;
        const jumlah_dos = row.querySelector('input[name="jumlah_dos[]"]').value || 0;
        const jumlah_pcs = row.querySelector('input[name="jumlah_pcs[]"]').value || 0;
        
        let total = (jumlah_dos * harga) + (jumlah_pcs * harga) - diskon;
        row.querySelector('input[name="total[]"]').value = total;
        calculateGrandTotal();
    }

    // Function to calculate grand total
    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('input[name="total[]"]').forEach(input => {
            grandTotal += parseFloat(input.value) || 0;
        });
        document.getElementById('grand_total').value = grandTotal;
    }
</script>
@endsection
