<!-- resources/views/outlets/edit.blade.php -->
@extends('layouts.master')
@section('title') Outlet Management @endsection

@section('content')
@component('common-components.breadcrumb')
@slot('pagetitle') Outlet Management @endslot
@slot('title') Edit Outlet @endslot
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
                            <h5 class="font-size-16 mb-1">Edit Outlet</h5>
                            <p class="text-muted text-truncate mb-0">Form ini digunakan untuk mengedit data outlet.</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('outlets.update', $outlet->id_outlet) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="nama" class="col-md-2 col-form-label">Nama Outlet</label>
                            <div class="col-md-4">
                                <input type="text" id="nama" name="nama" class="form-control"
                                    placeholder="Masukkan nama outlet" value="{{ old('nama', $outlet->nama) }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama_pemilik" class="col-md-2 col-form-label">Nama Pemilik</label>
                            <div class="col-md-4">
                                <input type="text" id="nama_pemilik" name="nama_pemilik" class="form-control"
                                    placeholder="Masukkan nama pemilik" value="{{ old('nama_pemilik', $outlet->nama_pemilik) }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="NIK" class="col-md-2 col-form-label">NIK</label>
                            <div class="col-md-4">
                                <input type="number" id="NIK" name="NIK" class="form-control" placeholder="Masukkan NIK"
                                    value="{{ old('NIK', $outlet->NIK) }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="phone" class="col-md-2 col-form-label">No HP</label>
                            <div class="col-md-4">
                                <input type="number" id="phone" name="phone" class="form-control"
                                    placeholder="Masukkan nomor telepon" value="{{ old('phone', $outlet->phone) }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="id_sales" class="col-md-2 col-form-label">Sales</label>
                            <div class="col-md-4">
                                <select name="id_sales" id="id_sales" class="form-select" required>
                                    <option value="">Pilih Sales</option>
                                    @foreach ($sales as $id => $full_name)
                                    <option value="{{ $id }}" {{ old('id_sales', $outlet->id_sales) == $id ? 'selected' : '' }}>{{ $full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="id_zona" class="col-md-2 col-form-label">Zona</label>
                            <div class="col-md-4">
                                <select name="id_zona" id="id_zona" class="form-select" required>
                                    <option value="">Pilih Zona</option>
                                    @foreach ($zonas as $id => $name)
                                    <option value="{{ $id }}" {{ old('id_zona', $outlet->id_zona) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="alamat" class="col-md-2 col-form-label">Alamat</label>
                            <div class="col-md-10">
                                <textarea id="alamat" name="alamat" class="form-control" placeholder="Masukkan alamat" required>{{ old('alamat', $outlet->alamat) }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="ktp" class="col-md-2 col-form-label">Upload KTP</label>
                            <div class="col-md-4">
                                <input type="file" name="ktp" class="form-control"
                                    accept="image/jpeg, image/png, image/jpg, image/gif, image/bmp, image/tiff" id="ktp"
                                    onchange="previewImage('.tampil-ktp', this.files[0])">
                                <br>
                                <div class="tampil-ktp">
                                    @if($outlet->ktp)
                                    <img src="{{ asset('storage/' . $outlet->ktp) }}" class="img-thumbnail" style="max-width: 400px;" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="latitude" class="col-md-2 col-form-label">Latitude</label>
                            <div class="col-md-4">
                                <input type="text" id="latitude" name="latitude" class="form-control"
                                    placeholder="Masukkan latitude" value="{{ old('latitude', $outlet->latitude) }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="longitude" class="col-md-2 col-form-label">Longitude</label>
                            <div class="col-md-4">
                                <input type="text" id="longitude" name="longitude" class="form-control"
                                    placeholder="Masukkan longitude" value="{{ old('longitude', $outlet->longitude) }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div id="map" style="width: 100%; height: 500px;"></div>
                            </div>
                        </div>


                                <button type="submit" class="btn btn-primary"> <i class="uil uil-save me-1"></i> Update</button>
                                <a href="{{ route('outlets.index') }}" class="btn btn-warning">Kembali</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
        // Koordinat default diambil dari database
        const latFromDB = {{ $outlet->latitude }};
        const lonFromDB = {{ $outlet->longitude }};
        const outletName = "{{ $outlet->nama }}";

        // Inisialisasi peta dengan koordinat dari database
        const map = L.map('map').setView([latFromDB, lonFromDB], 13);

        // Set up the tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker dengan posisi yang dapat di-drag
        let marker = L.marker([latFromDB, lonFromDB], { draggable: true }).addTo(map)
            .bindPopup("<b>" + outletName + "</b>")
            .openPopup();

        // Update latitude dan longitude ketika marker di-drag
        marker.on('dragend', function (event) {
            const position = event.target.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });

        // Tambahkan event untuk update marker ketika peta diklik
        map.on('click', function(e) {
            marker.setLatLng(e.latlng); // Pindahkan marker ke lokasi yang diklik
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });

        // Update map dan marker jika user mengubah input latitude atau longitude
        document.getElementById('latitude').addEventListener('change', function () {
            const lat = parseFloat(this.value);
            const lon = parseFloat(document.getElementById('longitude').value);
            if (!isNaN(lat) && !isNaN(lon)) {
                map.setView([lat, lon], 13);
                marker.setLatLng([lat, lon]);
            }
        });

        document.getElementById('longitude').addEventListener('change', function () {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lon = parseFloat(this.value);
            if (!isNaN(lat) && !isNaN(lon)) {
                map.setView([lat, lon], 13);
                marker.setLatLng([lat, lon]);
            }
        });
    });
</script>
@endsection