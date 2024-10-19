<!-- resources/views/outlets/index.blade.php -->
@extends('layouts.master')
@section('title') Outlet Management @endsection

@section('content')
@component('common-components.breadcrumb')
@slot('pagetitle') Dashboard @endslot
@slot('title') Outlet Management @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <a href="{{ route('outlets.create') }}" class="btn btn-success btn-xs waves-effect waves-light">
                                <i class="mdi mdi-plus me-2"></i> Tambah Outlet
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-inline float-md-end mb-3">
                            <div class="search-box ms-2">
                                <div class="position-relative">
                                    <!-- Form pencarian -->
                                    <form action="{{ route('outlets.index') }}" method="GET">
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
                <!-- end row -->
                <div class="table-responsive mb-4">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Outlet</th>
                                <th>Nama Pemilik</th>
                                <th>NIK</th>
                                <th>Nomor HP</th>
                                <th>Sales</th>
                                <th>Zona</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($outlets as $index => $outlet)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $outlet->nama }}</td>
                                    <td>{{ $outlet->nama_pemilik }}</td>
                                    <td>{{ $outlet->NIK }}</td>
                                    <td>{{ $outlet->phone }}</td>
                                    <td>{{ $outlet->sales->full_name }}</td>
                                    <td>{{ $outlet->zona->nama }}</td>
                                    <td>{{ $outlet->alamat }}</td>
                                    <td>
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item">
                                                <a href="{{ route('outlets.edit', $outlet->id_outlet) }}" class="px-2 text-primary"><i
                                                        class="uil uil-pen font-size-18"></i></a>
                                            </li>
                                            <li class="list-inline-item">
                                                <form action="{{ route('outlets.destroy', $outlet->id_outlet) }}" method="POST" class="form-delete" style="display:inline;">
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
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data outlet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- end table-responsive -->
                <div class="d-flex justify-content-end">
                    {{ $outlets->links() }} <!-- Pagination -->
                </div>
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

@endsection