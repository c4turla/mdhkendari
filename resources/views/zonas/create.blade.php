@extends('layouts.master')
@section('title') Zona Management @endsection

@section('content')
@component('common-components.breadcrumb')
    @slot('pagetitle') Zona Management @endslot
    @slot('title') Tambah Zona @endslot
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
                            <h5 class="font-size-16 mb-1">Tambah Zona</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk menambah data zona.</p>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <form action="{{ route('zonas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3 row">
                            <label for="nama" class="col-md-2 col-form-label">Nama Zona</label>
                            <div class="col-md-4">
                                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama zona" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="keterangan" class="col-md-2 col-form-label">Keterangan</label>
                            <div class="col-md-10">
                                <textarea id="keterangan" name="keterangan" class="form-control" placeholder="Masukkan keterangan zona (opsional)"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="keterangan" class="col-md-2 col-form-label">Depo</label>
                            <div class="col-md-4">
                            <select class="form-control form-select" id="id_depo" name="id_depo" required>
                                <option value="">Pilih Depo</option>
                                @foreach ($depos as $depo)
                                <option value="{{ $depo->id_depo }}">
                                    {{ $depo->nama_depo }}
                                </option>
                                 @endforeach
                            </select>
                            </div>
                        </div>

                        <div class="row justify-content-end">
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-success"> <i class="uil uil-save me-1"></i> Simpan</button>
                                <a href="{{ route('zonas.index') }}" class="btn btn-warning">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end custom-accordion -->
    </div> <!-- end col-xl-12 -->
</div> <!-- end row -->

@endsection

