@extends('layouts.master')
@section('title') Tambah Barang Return @endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('common-components.breadcrumb')
    @slot('pagetitle') Barang Return @endslot
    @slot('title') Tambah Barang Return @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <i class="uil uil-receipt text-primary h2"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <h5 class="font-size-16 mb-1">Tambah Barang Return</h5>
                        <p class="text-muted text-truncate mb-0">Form ini digunakan untuk menambah data barang return.</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('barang_return.store') }}" method="POST">
                    @csrf
                    <div class="mb-3 row">
                        <label for="tanggal_return" class="col-md-2 col-form-label">Faktur Penjualan</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="nomor_bukti" id="nomor_bukti" class="form-control" readonly placeholder="Pilih Faktur">
                                <button type="button" class="btn btn-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#fakturModal">Pilih Faktur</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_faktur" id="id_faktur" class="form-control">
                    <div class="mb-3 row">
                        <label for="tanggal_return" class="col-md-2 col-form-label">Tanggal Return</label>
                        <div class="col-md-2">
                            <input type="date" id="tanggal_return" name="tanggal_return" class="form-control" required>
                        </div>
                    </div>
                    <hr>
            
                    <div id="faktur-details"></div>
            
                    <button type="submit" class="btn btn-primary"><i class="uil uil-save me-1"></i> Simpan</button>
                    <a href="{{ route('barang_return.index') }}" class="btn btn-warning">Kembali</a>
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<!-- Modal Faktur -->
<div id="fakturModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myModalLabel">Pilih Faktur Untuk Di Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap"
                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>Nomor Faktur</th>
                            <th>Tanggal</th>
                            <th>Nama Toko</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                         @foreach($fakturs as $faktur)
                            <tr>
                                <td>{{ $faktur->nomor_bukti }}</td>
                                <td>{{ $faktur->tanggal_buat }}</td>
                                <td>{{ $faktur->outlet->nama }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary select-faktur" data-id="{{ $faktur->id_faktur }}" data-nomor_bukti="{{ $faktur->nomor_bukti }}" data-bs-dismiss="modal" data-target="#fakturModal">Pilih</button>
                                </td>
                            </tr>
                        @endforeach 
                    </tbody>
                </table>
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning waves-effect" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection
@section('script')
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
<!-- jquery-steps js -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.select-faktur').forEach(button => {
            button.addEventListener('click', function () {
                const fakturId = this.getAttribute('data-id');
                const nomorBukti = this.getAttribute('data-nomor_bukti');
                
                document.getElementById('id_faktur').value = fakturId;
                document.getElementById('nomor_bukti').value = nomorBukti;

                fetch(`/barang_return/getFakturDetails/${fakturId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.details) {
                            let html = '<strong>Detail Barang Return</strong><table class="table table-bordered"><thead><tr><th>ID Barang</th><th>Nama Barang</th><th>Jumlah Dos</th><th>Jumlah Pcs</th><th>Aksi</th></tr></thead><tbody>';
                            data.details.forEach(detail => {
                                html += `<tr>
                                    <td><input type="hidden" name="barangs[${detail.id_barang}][id_barang]" value="${detail.id_barang}">${detail.id_barang}</td>
                                    <td>${detail.nama_barang}</td>
                                    <td><input type="number" name="barangs[${detail.id_barang}][jumlah_dos]" class="form-control" value="${detail.jumlah_dos}"></td>
                                    <td><input type="number" name="barangs[${detail.id_barang}][jumlah_pcs]" class="form-control" value="${detail.jumlah_pcs}"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                                </tr>`;
                            });
                            html += '</tbody></table>';
                            document.getElementById('faktur-details').innerHTML = html;
                            $('#fakturModal').modal('hide');

                            // Add event listener for remove buttons
                            document.querySelectorAll('.remove-row').forEach(button => {
                                button.addEventListener('click', function () {
                                    this.closest('tr').remove();
                                });
                            });
                        } else {
                            console.error('Unexpected data format:', data);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    });
</script>

@endsection
