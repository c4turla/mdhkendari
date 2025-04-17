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
<link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
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
                                <select id="outlet_id" name="id_outlet" class="form-control select2" required>
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
                                    <select id="barang_1" name="barang[]" class="form-control select2" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id_barang }}">{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="satuan_1">Satuan</label>
                                    <select id="satuan_1" name="satuan[]" class="form-control select2" required>
                                        <option value="">Pilih Satuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="jumlah_1">Jumlah</label>
                                    <input type="number" id="jumlah_1" name="jumlah[]" class="form-control" placeholder="Jumlah" oninput="calculateTotal(this)" value="0" min="0">
                                </div>
                                <div>
                                    <label for="harga_1">Harga</label>
                                    <input type="number" id="harga_1" name="harga[]" class="form-control" placeholder="Harga" readonly>
                                </div>
                                <div>
                                    <label for="diskon_1">Diskon</label>
                                    <input type="number" id="diskon_1" name="diskon[]" class="form-control" placeholder="Diskon" oninput="calculateTotal(this)" value="0">
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

function addItem() {
    itemCount++;
    const itemRow = document.createElement('div');
    itemRow.classList.add('item-row');
    
    let barangOptions = '<option value="">Pilih Barang</option>';
    @foreach($barangs as $barang)
        barangOptions += `<option value="{{ $barang->id_barang }}">{{ $barang->nama_barang }}</option>`;
    @endforeach

    itemRow.innerHTML = `
            <div> 
                <select id="barang_${itemCount}" name="barang[]" class="form-control select2" required>
                    ${barangOptions}
                </select>
            </div>
            <div> 
                <select id="satuan_${itemCount}" name="satuan[]" class="form-control select2" required>
                    <option value="">Pilih Satuan</option>
                </select>
            </div>
            <div>          
                <input type="number" id="jumlah_${itemCount}" name="jumlah[]" class="form-control" placeholder="Jumlah" oninput="calculateTotal(this)" value="0" min="0">
            </div>
            <div>          
                <input type="number" id="harga_${itemCount}" name="harga[]" class="form-control" placeholder="Harga" readonly>
            </div>
            <div>            
                <input type="number" id="diskon_${itemCount}" name="diskon[]" class="form-control" placeholder="Diskon" oninput="calculateTotal(this)" value="0">
            </div>
            <div>          
                <input type="number" id="total_${itemCount}" name="total[]" class="form-control" placeholder="Total" readonly>
            </div>
            <i class="remove-item fa fa-trash" style="cursor: pointer;" onclick="removeItem(this)"></i>
        `;
    document.getElementById('barang-list').appendChild(itemRow);

    attachBarangChangeEvent(`#barang_${itemCount}`);
    attachSatuanChangeEvent(`#satuan_${itemCount}`);
    $('.select2').select2();
}

function removeItem(element) {
    element.parentElement.remove();
    calculateGrandTotal();
}

function calculateTotal(element) {
    const row = element.closest('.item-row');
    const jumlah = parseFloat(row.querySelector('input[name="jumlah[]"]').value) || 0;
    const harga = parseFloat(row.querySelector('input[name="harga[]"]').value) || 0;
    const diskon = parseFloat(row.querySelector('input[name="diskon[]"]').value) || 0;

    const total = (jumlah * harga) - diskon;
    row.querySelector('input[name="total[]"]').value = total.toFixed(2);
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('input[name="total[]"]').forEach(total => {
        grandTotal += parseFloat(total.value) || 0;
    });
    document.getElementById('grand_total').value = grandTotal.toFixed(2);
}

function attachBarangChangeEvent(selector) {
    $(selector).change(function() {
        const row = $(this).closest('.item-row');
        const barangId = $(this).val();
        const zonaId = $('#zona_id').val();
        const satuanSelect = row.find('select[name="satuan[]"]');

        if (!zonaId) {
            Swal.fire({
                title: "Peringatan",
                text: "Anda Belum Memilih Outlet.",
                icon: "warning"
            });
            return;
        }

        if (barangId) {
            console.log('Mengambil data satuan dan harga untuk barang:', barangId, 'dan zona:', zonaId); // Debugging: Tampilkan barang dan zona yang dipilih

            $.ajax({
                url: '/get-satuan',
                type: 'GET',
                data: { zona_id: zonaId, barang_id: barangId },
                success: function(response) {
                    console.log('Response dari server:', response); // Debugging: Tampilkan response dari server

                    satuanSelect.empty().append('<option value="">Pilih Satuan</option>');
                    $.each(response.satuan, function(index, satuan) {
                        satuanSelect.append(`<option value="${satuan}">${satuan}</option>`);
                    });

                    // Simpan data harga ke row
                    row.data('hargaPerDos', response.harga_per_dos);
                    row.data('hargaPerPcs', response.harga_per_pcs);
                    row.data('hargaLainnya', response.harga_lainnya);

                    // Debugging: Tampilkan data harga yang disimpan
                    console.log('Data harga per DOS disimpan:', row.data('hargaPerDos'));
                    console.log('Data harga per PCS disimpan:', row.data('hargaPerPcs'));
                    console.log('Data harga lainnya disimpan:', row.data('hargaLainnya'));
                },
                error: function(jqXHR) {
                    console.error('Error saat mengambil data satuan dan harga:', jqXHR); // Debugging: Tampilkan error
                    if (jqXHR.status === 404) {
                        Swal.fire({
                            title: "Peringatan",
                            text: "Harga Barang belum ada di Harga Per Zona.",
                            icon: "warning"
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: "Terjadi kesalahan saat mengambil data harga.",
                            icon: "error"
                        });
                    }
                }
            });
        }
    });
}

function attachSatuanChangeEvent(selector) {
    $(document).on('change', selector, function() {
        const row = $(this).closest('.item-row');
        const satuan = $(this).val();
        let harga = 0;

        // Only proceed if satuan is selected
        if (!satuan) {
            return;
        }

        // Get the stored prices
        const hargaPerDos = parseFloat(row.data('hargaPerDos')) || 0;
        const hargaPerPcs = parseFloat(row.data('hargaPerPcs')) || 0;
        const hargaLainnya = parseFloat(row.data('hargaLainnya')) || 0;

        // Set harga based on selected satuan
        switch(satuan.toUpperCase()) {
            case 'DOS':
                harga = hargaPerDos;
                break;
            case 'PCS':
                harga = hargaPerPcs;
                break;
            default:
                harga = hargaLainnya;
        }

        // Update the harga input and recalculate total
        const hargaInput = row.find('input[name="harga[]"]');
        hargaInput.val(harga);
        calculateTotal(row.find('input[name="jumlah[]"]')[0]);
    });
}

$(document).ready(function() {
    attachBarangChangeEvent('#barang_1');
    attachSatuanChangeEvent('#satuan_1');
    
    $('#outlet_id').change(function() {
        const selectedOption = $(this).find('option:selected');
        $('#zona_id').val(selectedOption.data('zona-id'));
    });
});
</script>
<script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/form-advanced.init.js') }}"></script>
@endsection
