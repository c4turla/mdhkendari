@extends('layouts.master')
@section('title') Faktur Penjualan @endsection

@section('content')
@component('common-components.breadcrumb')
    @slot('pagetitle') Faktur Penjualan @endslot
    @slot('title') Daftar Faktur Penjualan @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <a href="{{ route('faktur_penjualan.create') }}" class="btn btn-success btn-xs waves-effect waves-light">
                                <i class="mdi mdi-plus me-2"></i> Tambah Faktur Penjualan
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-inline float-md-end mb-3">
                            <div class="search-box ms-2">
                                <div class="position-relative">
                                    <form action="{{ route('faktur_penjualan.index') }}" method="GET">
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
                            <th>Nomor Faktur</th>
                            <th>Outlet</th>
                            <th>Tanggal</th>
                            <th>Tanggal JT</th>
                            <th>Pembayaran</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faktur_penjualan as $index => $faktur)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $faktur->nomor_bukti }}</td>
                            <td>{{ $faktur->outlet->nama }}</td>
                            <td>{{ $faktur->tanggal_buat }}</td>
                            <td>{{ $faktur->tanggal_jatuh_tempo }}</td>
                            <td>
                                @if($faktur->cara_pembayaran == 'CASH')
                                    <span class="badge rounded-pill bg-success-subtle text-success font-size-12">CASH</span>
                                @elseif($faktur->cara_pembayaran == 'CREDIT')
                                    <span class="badge rounded-pill bg-danger-subtle text-danger font-size-12">CREDIT</span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($faktur->grand_total, 0, ',', '.') }}</td>
                            <td>
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <a href="javascript:void(0)" onclick="showDetail('{{ route('faktur_penjualan.show', $faktur->id_faktur) }}')" class="px-2 text-primary">
                                            <i class="uil uil-eye font-size-18"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="{{ route('faktur.download.excel', $faktur->id_faktur) }}" class="px-2 text-primary" target="_blank"><i
                                                class="uil uil-print font-size-18"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="{{ route('faktur_penjualan.edit', $faktur->id_faktur) }}" class="px-2 text-primary"><i
                                                class="uil uil-pen font-size-18"></i></a>
                                    </li>
                                    <li class="list-inline-item">
                                        <form action="{{ route('faktur_penjualan.destroy', $faktur->id_faktur) }}" method="POST" class="form-delete" style="display:inline;">
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
            </div>
        </div>
    </div>
</div>
    <!-- Modal Structure -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Faktur Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning waves-effect" data-bs-dismiss="modal"><i class="uil uil-sign-out-alt"></i> Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault(); // Stop form submission
    
        var form = $(this).closest('form'); // Get the closest form to the button clicked
    
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit the form if confirmed
            }
        });
    });
</script>
<script>
    function showDetail(url) {
        // Show the modal
        $('#detailModal').modal('show');
        $('#modalContent').html('<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'); // Show loading spinner

        // Make AJAX request to fetch the detail data
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // Load the response into the modal body
                $('#modalContent').html(response);
            },
            error: function(xhr) {
                $('#modalContent').html('<p>Failed to load data.</p>');
            }
        });
    }
</script>
@endsection
