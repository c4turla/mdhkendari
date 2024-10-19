@extends('layouts.master')
@section('title') Faktur Penjualan Management @endsection
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
    @slot('title') Tambah Faktur Penjualan @endslot
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
                            <h5 class="font-size-16 mb-1">Tambah Faktur Penjualan</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk menambah data faktur penjualan.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Form untuk input data faktur penjualan -->
                    <form action="{{ route('faktur_penjualan.store') }}" method="POST">
                        @csrf
                        <!-- Nomor Bukti -->
                        <div class="mb-3 row">
                            <label for="nomor_bukti" class="col-md-2 col-form-label">Nomor Faktur</label>
                            <div class="col-md-4">
                                <input type="text" id="nomor_bukti" name="nomor_bukti" class="form-control" value="{{ $kode }}" readonly>
                            </div>
                        </div>
                        <!-- Outlet -->
                        <div class="mb-3 row">
                            <label for="id_outlet" class="col-md-2 col-form-label">Outlet</label>
                            <div class="col-md-4">
                                <select id="outlet_id" name="id_outlet" class="form-select" required>
                                    <option value="">Pilih Outlet</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id_outlet }}" data-zona-id="{{ $outlet->id_zona }}">{{ $outlet->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" id="zona_id" name="zona_id">
                        </div>

                        <!-- Tanggal Buat -->
                        <div class="mb-3 row">
                            <label for="tanggal_buat" class="col-md-2 col-form-label">Tanggal Buat</label>
                            <div class="col-md-2">
                                <input type="date" id="tanggal_buat" name="tanggal_buat" class="form-control" required>
                            </div>
                        </div>

                        <!-- Tanggal Jatuh Tempo -->
                        <div class="mb-3 row">
                            <label for="tanggal_jatuh_tempo" class="col-md-2 col-form-label">Tanggal Jatuh Tempo</label>
                            <div class="col-md-2">
                                <input type="date" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" class="form-control">
                            </div>
                        </div>

                        <!-- Cara Pembayaran -->
                        <div class="mb-3 row">
                            <label for="cara_pembayaran" class="col-md-2 col-form-label">Cara Pembayaran</label>
                            <div class="col-md-4">
                                <select id="cara_pembayaran" name="cara_pembayaran" class="form-select" required>
                                    <option value="CASH">CASH</option>
                                    <option value="CREDIT">CREDIT</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <button type="button" onclick="addItem()" class="btn btn-success btn-sm mb-3"><i class="uil uil-plus"></i> Tambah Barang</button>                        
                        
                        <!-- Daftar Barang -->
                        <div id="barang-list">
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
                                    <label for="jumlah_dos_1">Jumlah Dos</label>
                                    <input type="number" id="jumlah_dos_1" name="jumlah_dos[]" class="form-control" placeholder="Jumlah Dos" oninput="calculateTotal(this)" value="0" min="0">
                                </div>
                                <div>
                                    <label for="jumlah_pcs_1">Jumlah Pcs</label>
                                    <input type="number" id="jumlah_pcs_1" name="jumlah_pcs[]" class="form-control" placeholder="Jumlah Pcs" oninput="calculateTotal(this)" value="0" min="0">
                                </div>
                                <div>
                                    <label for="harga_1">Harga</label>
                                    <input type="number" id="harga_1" name="harga[]" class="form-control" placeholder="Harga" required>
                                </div>
                                <div>
                                    <label for="diskon_1">Diskon</label>
                                    <input type="number" id="diskon_1" name="diskon[]" class="form-control" placeholder="Diskon" oninput="calculateTotal(this)">
                                </div>
                                <div>
                                    <label for="total_1">Total</label>
                                    <input type="number" id="total_1" name="total[]" class="form-control" placeholder="Total" readonly>
                                </div>
                                <i class="remove-item fa fa-trash" style="cursor: pointer;" onclick="removeItem(this)"></i>
                            </div>
                        </div>

                        <!-- Grand Total -->
                        <div class="mb-3 row justify-content-end">
                            <label for="grand_total" class="col-md-1 col-form-label">Grand Total</label>
                            <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" id="grand_total" name="grand_total" class="form-control" placeholder="Grand Total" readonly>
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
    let itemCount = 1;

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
            <input type="number" id="jumlah_dos_${itemCount}" name="jumlah_dos[]" class="form-control" placeholder="Jumlah Dos" oninput="calculateTotal(this)" value="0" min="0">
        </div>
        <div>         
            <input type="number" id="jumlah_pcs_${itemCount}" name="jumlah_pcs[]" class="form-control" placeholder="Jumlah Pcs" oninput="calculateTotal(this)" value="0" min="0">
        </div>
        <div>          
            <input type="number" id="harga_${itemCount}" name="harga[]" class="form-control" placeholder="Harga" readonly>
        </div>
        <div>            
            <input type="number" id="diskon_${itemCount}" name="diskon[]" class="form-control" placeholder="Diskon" oninput="calculateTotal(this)">
        </div>
        <div>          
            <input type="number" id="total_${itemCount}" name="total[]" class="form-control" placeholder="Total" readonly>
        </div>
        <i class="remove-item fa fa-trash" style="cursor: pointer;" onclick="removeItem(this)"></i>
    `;
    document.getElementById('barang-list').appendChild(itemRow);

    // Attach change event listener for the new barang dropdown
    attachBarangChangeEvent(`#barang_${itemCount}`);
}

// Function to remove an item row
function removeItem(element) {
    element.parentElement.remove();
    calculateGrandTotal();
}

// Function to calculate total for each item
function calculateTotal(element) {
    const row = element.closest('.item-row');
    const jumlahDos = parseFloat(row.querySelector('input[name="jumlah_dos[]"]').value) || 0;
    const jumlahPcs = parseFloat(row.querySelector('input[name="jumlah_pcs[]"]').value) || 0;
    const harga = parseFloat(row.querySelector('input[name="harga[]"]').value) || 0;
    const diskon = parseFloat(row.querySelector('input[name="diskon[]"]').value) || 0;

    const total = ((jumlahDos * harga) + (jumlahPcs * harga)) - diskon;
    row.querySelector('input[name="total[]"]').value = total.toFixed(2);

    calculateGrandTotal();
}

// Function to calculate grand total for all items
function calculateGrandTotal() {
    let grandTotal = 0;
    const totals = document.querySelectorAll('input[name="total[]"]');
    totals.forEach(total => {
        grandTotal += parseFloat(total.value) || 0;
    });
    document.getElementById('grand_total').value = grandTotal.toFixed(2);
}


// Function to handle barang selection and fetch price based on barang_id and zona_id
function attachBarangChangeEvent(selector) {
    let zonaId = null;

    // Ketika outlet berubah, simpan zona_id
    $('#outlet_id').change(function() {
        zonaId = $(this).find(':selected').data('zona-id');
        $('#zona_id').val(zonaId); // Simpan zona_id ke dalam field tersembunyi (hidden input) jika diperlukan

        // Reset semua harga, total, dan barang jika outlet berubah
        $('select[name="barang[]"]').val(''); // Reset barang selection
        $('input[name="harga[]"]').val('');   // Clear harga fields
        $('input[name="total[]"]').val('');   // Clear total fields
        calculateGrandTotal();                // Recalculate grand total
    });

    // Handle barang selection untuk setiap row
    $(selector).change(function() {
    const row = $(this).closest('.item-row');
    const barangId = $(this).val();
    const zonaId = $('#zona_id').val(); // Pastikan zona_id terisi

    if (barangId && zonaId) {
        // Ambil harga berdasarkan barang_id dan zona_id
        $.ajax({
            url: '/get-harga-barang',
            type: 'GET',
            data: {
                barang_id: barangId,
                zona_id: zonaId
            },
            success: function(data) {
                // Simpan harga di atribut data row untuk digunakan nanti
                row.data('hargaPerDos', data.harga_per_dos);
                row.data('hargaPerPcs', data.harga_per_pcs);
            },
            error: function(jqXHR) {
                // Tangani kesalahan jika terjadi
                if (jqXHR.status === 404) {
                    // Menampilkan SweetAlert jika tidak ada harga
                    Swal.fire({
                        title: "Peringatan",
                        text: "Harga Barang belum ada di Harga Per Zona.",
                        icon: "warning",
                        button: "OK",
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Terjadi kesalahan saat mengambil data harga.",
                        icon: "error",
                        button: "OK",
                    });
                }
            }
        });
    } else {
        Swal.fire({
            title: "Peringatan",
            text: "Anda Belum Memilih Outlet.",
            icon: "warning",
            button: "OK",
        });
    }
});


    // Ketika jumlah dos atau pcs diisi, tampilkan harga dan hitung total
    $('input[name="jumlah_dos[]"], input[name="jumlah_pcs[]"]').on('input', function() {
    const row = $(this).closest('.item-row');
    const jumlahDos = parseFloat(row.find('input[name="jumlah_dos[]"]').val()) || 0;
    const jumlahPcs = parseFloat(row.find('input[name="jumlah_pcs[]"]').val()) || 0;

    // Ambil harga yang sudah disimpan sebelumnya di data row
    const hargaPerDos = row.data('hargaPerDos') || 0;
    const hargaPerPcs = row.data('hargaPerPcs') || 0;

    // Jika user mengisi jumlah dos, tampilkan harga per dos
    if (jumlahDos > 0) {
        row.find('input[name="harga[]"]').val(hargaPerDos); // Tampilkan harga per dos
    }

    // Jika user mengisi jumlah pcs, tampilkan harga per pcs
    if (jumlahPcs > 0) {
        row.find('input[name="harga[]"]').val(hargaPerPcs); // Tampilkan harga per pcs
    }

    // Hitung total harga berdasarkan dos dan pcs
    const total = (jumlahDos * hargaPerDos) + (jumlahPcs * hargaPerPcs);
    row.find('input[name="total[]"]').val(total.toFixed(2));

    // Hitung grand total
    calculateGrandTotal();
    });
}


// Initial attach of change event for the first item row
$(document).ready(function() {
    attachBarangChangeEvent('#barang_1');
});

</script>
@endsection
