@extends('layouts.master')
@section('title') Barang Return @endsection

@section('content')
@component('common-components.breadcrumb')
    @slot('pagetitle') Barang Return @endslot
    @slot('title') Daftar Barang Return @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <a href="{{ route('barang_return.create') }}" class="btn btn-success btn-xs waves-effect waves-light">
                                <i class="mdi mdi-plus me-2"></i> Tambah Barang Return
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-inline float-md-end mb-3">
                            <div class="search-box ms-2">
                                <div class="position-relative">
                                    <form action="{{ route('barang_return.index') }}" method="GET">
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
                            <th>Nama Barang</th>
                            <th>Jumlah Dos</th>
                            <th>Jumlah Pcs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangReturns as $index => $barangReturn)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $barangReturn->faktur->nomor_bukti }}</td>
                            <td>{{ $barangReturn->barang->nama_barang }}</td>
                            <td>{{ $barangReturn->jumlah_dos }}</td>
                            <td>{{ $barangReturn->jumlah_pcs }}</td>
                            <td>
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">
                                        <form action="{{ route('barang_return.destroy', $barangReturn->id_return) }}" method="POST" class="form-delete" style="display:inline;">
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
                    <h5 class="modal-title" id="detailModalLabel">Detail Barang Return</h5>
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
