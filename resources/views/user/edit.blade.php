@extends('layouts.master')
@section('title') User Management @endsection
@section('content')
@component('common-components.breadcrumb')
@slot('pagetitle') User Management @endslot
@slot('title') Edit Pengguna @endslot
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
                            <h5 class="font-size-16 mb-1">Edit Pengguna</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk mengedit data pengguna.</p>
                        </div>
                    </div>
                </div>

                <div class="collapse show">
                    <div class="p-4 border-top">
                        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3 mb-4">
                                            <label class="form-label" for="billing-name">Username</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Enter Username" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="mb-3 mb-4">
                                            <label class="form-label" for="billing-email-address">Nama Lengkap</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}" placeholder="Nama Lengkap" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3 mb-4">
                                            <label class="form-label" for="billing-email-address">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Enter email" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3 mb-4">
                                            <label class="form-label" for="billing-phone">Nomor Handphone</label>
                                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Nomor Handphone" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="billing-address">Alamat</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Alamat Lengkap">{{ old('address', $user->address) }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-4 mb-lg-0">
                                            <label class="form-label">Level Pengguna</label>
                                            <select class="form-control form-select" title="user_level" id="user_level" name="user_level" required>
                                                <option value="">Pilih Level</option>
                                                <option value="admin" {{ $user->user_level == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="operator" {{ $user->user_level == 'operator' ? 'selected' : '' }}>Operator</option>
                                                <option value="sales" {{ $user->user_level == 'sales' ? 'selected' : '' }}>Sales</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="mb-4 mb-lg-0">
                                            <label class="form-label" for="billing-city">Password (Kosongkan jika tidak ingin mengubah)</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="mb-0">
                                            <label class="form-label" for="zip-code">Photo</label>
                                            <input type="file" class="form-control" id="photo" name="photo">
                                            @if ($user->photo)
                                                <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" class="mt-2" style="max-width: 150px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-4">
                                <div class="col">
                                    <button type="submit" class="btn btn-primary"> <i class="uil uil-save me-1"></i> Simpan</button>
                                    <a href="{{ route('user.index') }}" class="btn btn-warning">
                                        <i class="uil uil-angle-double-right"></i> Kembali
                                    </a>
                                </div> <!-- end col -->
                            </div> <!-- end row-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: 
                '<ul>' +
                @foreach ($errors->all() as $error)
                    '<li>{{ $error }}</li>' +
                @endforeach
                '</ul>',
        });
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session("success") }}',
        });
    </script>
@endif
@endsection
