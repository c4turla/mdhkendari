@extends('layouts.master')
@section('title') Peta Outlet @endsection
@section('css')
<!-- plugin css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.css" />
@endsection
@section('content')
@component('common-components.breadcrumb')
    @slot('pagetitle') Dashboard @endslot
    @slot('title') Peta Outlet @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Peta Semua Outlet</h4>
                <div id="map" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.js"></script>

<script>
    // Inisialisasi peta
    var map = L.map('map', {
        center: [-3.9862, 122.5144],  // Koordinat awal
        zoom: 13,                     // Zoom awal
        fullscreenControl: true,      // Aktifkan kontrol fullscreen
        fullscreenControlOptions: {
            position: 'topleft'      // Posisi tombol fullscreen (opsional)
        }
    });

    // Tambahkan tile layer dari OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Definisikan custom icon untuk marker
    var shopIcon = L.icon({
        iconUrl: "{{ asset('assets/images/shop.png') }}",  // Path ikon kustom
        iconSize: [28, 28],  // Ukuran ikon
        iconAnchor: [22, 38],  // Posisi anchor ikon (koordinat dasar marker)
        popupAnchor: [-3, -38],  // Posisi popup relatif terhadap ikon
    });

    // Data marker outlet dari controller (data outlet dengan latitude dan longitude)
    var outlets = @json($outlets);

    // Loop melalui data outlet dan tambahkan marker ke peta dengan ikon kustom
    outlets.forEach(function(outlet) {
        var marker = L.marker([outlet.latitude, outlet.longitude], {icon: shopIcon}).addTo(map)
            .bindPopup("<b>" + outlet.nama + "</b><br>Lat: " + outlet.latitude + ", Long: " + outlet.longitude);
    });
</script>
@endsection
