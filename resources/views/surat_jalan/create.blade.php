@extends('layouts.master')

@section('title') Tambah Surat Jalan @endsection
@section('css')
<!-- plugin css -->
<link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
@component('common-components.breadcrumb')
    @slot('pagetitle') Surat Jalan @endslot
    @slot('title') Tambah Surat Jalan @endslot
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
                            <h5 class="font-size-16 mb-1">Tambah Surat Jalan</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk menambah surat jalan dengan memilih beberapa faktur.</p>
                        </div>
                    </div>
                </div>

                <div class="collapse show">
                    <div class="p-4 border-top">
                        <form action="{{ route('surat_jalan.store') }}" method="POST">
                            @csrf

                            <div class="mb-3 row">
                                <label for="no_surat_jalan" class="col-md-2 col-form-label">Nomor Surat Jalan</label>
                                <div class="col-md-4">
                                    <input type="text" id="no_surat_jalan" name="no_surat_jalan" class="form-control" value="{{ $kode }}" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label for="fakturs" class="col-md-2 col-form-label">Nomor Faktur</label>
                                <div class="col-md-10">
                                    <select id="fakturs" name="fakturs[]" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Pilih Faktur" required>
                                        @foreach($fakturs as $faktur)
                                            <option value="{{ $faktur->id_faktur }}">{{ $faktur->nomor_bukti }} - {{ $faktur->outlet->nama }} - {{ $faktur->outlet->sales->full_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('fakturs')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="id_sales" class="col-md-2 col-form-label">Nama Sales</label>
                                <div class="col-md-4">
                                    <select id="id_sales" name="id_sales" class="form-select" required>
                                        <option value="" disabled selected>Pilih Sales</option>
                                        @foreach ($sales as $id => $full_name)
                                            <option value="{{ $id }}" {{ old('id_sales')==$id ? 'selected' : '' }}>{{ $full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_sales')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="tanggal_surat" class="col-md-2 col-form-label">Tanggal Surat Jalan</label>
                                <div class="col-md-2">
                                    <input type="date" id="tanggal_surat" name="tanggal_surat" class="form-control" required>
                                    @error('tanggal_surat')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="keterangan" class="col-md-2 col-form-label">Keterangan (Opsional)</label>
                                <div class="col-md-10">
                                    <textarea id="keterangan" name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-10 offset-md-2">
                                    <button type="submit" class="btn btn-primary"><i class="uil uil-save me-1"></i> Simpan</button>
                                    <a href="{{ route('surat_jalan.index') }}" class="btn btn-warning">Kembali</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/form-advanced.init.js') }}"></script>
@endsection
