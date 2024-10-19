@extends('layouts.master')
@section('title') @lang('translation.Dashboard') @endsection
@section('content')
@component('common-components.breadcrumb')
@slot('pagetitle') MDH Apps @endslot
@slot('title') Dashboard @endslot
@endcomponent

<div class="row">
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <div id="total-revenue-chart" data-colors='["--bs-primary"]'></div>
                </div>
                <div>
                    <h4 class="mb-1 mt-1">Rp <span data-plugin="counterup">{{ number_format($totalRevenue, 0, '.', ',')
                            }}</span></h4>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
                <p class="text-muted mt-3 mb-0">
                    @if ($percentageChange >= 0)
                    <span class="text-success me-1">
                        <i class="mdi mdi-arrow-up-bold me-1"></i>{{ number_format($percentageChange, 2) }}%
                    </span>
                    @else
                    <span class="text-danger me-1">
                        <i class="mdi mdi-arrow-down-bold me-1"></i>{{ number_format(abs($percentageChange), 2) }}%
                    </span>
                    @endif
                    since last week
                </p>
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <div id="orders-chart" data-colors='["--bs-success"]'> </div>
                </div>
                <div>
                    <h4 class="mb-1 mt-1"><span data-plugin="counterup">{{ $totalOutlets }}</span></h4>
                    <p class="text-muted mb-0">Outlet</p>
                </div>
                <p class="text-muted mt-3 mb-0">
                    <span class="{{ $isIncrease ? 'text-success' : 'text-danger' }} me-1">
                        <i class="mdi {{ $isIncrease ? 'mdi-arrow-up-bold' : 'mdi-arrow-down-bold' }} me-1"></i>{{
                        abs($percentageChange) }}%
                    </span> since last week
                </p>
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <div id="customers-chart" data-colors='["--bs-primary"]'> </div>
                </div>
                <div>
                    <h4 class="mb-1 mt-1"><span data-plugin="counterup">45,254</span></h4>
                    <p class="text-muted mb-0">Customers</p>
                </div>
                <p class="text-muted mt-3 mb-0"><span class="text-danger me-1"><i
                            class="mdi mdi-arrow-down-bold me-1"></i>6.24%</span> since last week
                </p>
            </div>
        </div>
    </div> <!-- end col-->

    <div class="col-md-6 col-xl-3">

        <div class="card">
            <div class="card-body">
                <div class="float-end mt-2">
                    <div id="growth-chart" data-colors='["--bs-warning"]'></div>
                </div>
                <div>
                    <h4 class="mb-1 mt-1">+ <span data-plugin="counterup">12.58</span>%</h4>
                    <p class="text-muted mb-0">Growth</p>
                </div>
                <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i
                            class="mdi mdi-arrow-up-bold me-1"></i>10.51%</span> since last week
                </p>
            </div>
        </div>
    </div> <!-- end col-->
</div> <!-- end row-->

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="float-end">
                    <div class="dropdown">
                        <a class="dropdown-toggle text-reset" href="#" id="dropdownMenuButton5"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="fw-semibold">Sort By:</span> <span class="text-muted">Yearly<i
                                    class="mdi mdi-chevron-down ms-1"></i></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton5">
                            <a class="dropdown-item" href="#">Monthly</a>
                            <a class="dropdown-item" href="#">Yearly</a>
                            <a class="dropdown-item" href="#">Weekly</a>
                        </div>
                    </div>
                </div>
                <h4 class="card-title mb-4">Sales Analytics</h4>

                <div class="mt-1">
                    <ul class="list-inline main-chart mb-0">
                        <li class="list-inline-item chart-border-left me-0 border-0">
                            <h3 class="text-primary">$<span data-plugin="counterup">2,371</span><span
                                    class="text-muted d-inline-block font-size-15 ms-3">Income</span></h3>
                        </li>
                        <li class="list-inline-item chart-border-left me-0">
                            <h3><span data-plugin="counterup">258</span><span
                                    class="text-muted d-inline-block font-size-15 ms-3">Sales</span>
                            </h3>
                        </li>
                        <li class="list-inline-item chart-border-left me-0">
                            <h3><span data-plugin="counterup">3.6</span>%<span
                                    class="text-muted d-inline-block font-size-15 ms-3">Conversation Ratio</span></h3>
                        </li>
                    </ul>
                </div>

                <div class="mt-3">
                    <div id="sales-analytics-chart" data-colors='["--bs-primary", "#dfe2e6", "--bs-warning"]'
                        class="apex-charts" dir="ltr"></div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Top 3 Sales</h4>
                @if($topSales->count() > 0)
                @foreach($topSales as $sales)

                <div class="row align-items-center g-0 mt-3">
                    <div class="col-sm-9">
                        <p class="text-truncate mt-1 mb-0">
                            <i class="mdi mdi-circle-medium text-primary me-2"></i>
                            {{ $sales->sales->full_name ?? 'Sales not found' }}
                            <!-- Nama sales dari relasi -->
                        </p>
                    </div>
                </div> <!-- end row -->
                @endforeach
                @else
                <p>No sales data available.</p>
                @endif
            </div> <!-- end card-body-->
        </div> <!-- end card-->

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Top 5 Barang Laku</h4>
                @foreach($topProducts as $product)
                @php
                // Menghitung persentase terjual dari total
                $percentageSold = ($product->total_terjual / $topProducts->max('total_terjual')) * 100;

                // Menentukan kelas progress bar berdasarkan persentase
                if ($percentageSold > 70) {
                $progressClass = 'bg-success';
                } elseif ($percentageSold >= 50) {
                $progressClass = 'bg-primary';
                } else {
                $progressClass = 'bg-warning';
                }
                @endphp

                <div class="row align-items-center g-0 mt-3">
                    <div class="col-sm-9">
                        <p class="text-truncate mt-1 mb-0">
                            <i class="mdi mdi-circle-medium text-primary me-2"></i>
                            {{ $product->barang->nama_barang }}
                        </p>
                    </div>
                    <div class="col-sm-3">
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar-animated progress-bar {{ $progressClass }}" role="progressbar"
                                style="width: {{ $percentageSold }}%" aria-valuenow="{{ $percentageSold }}"
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div> <!-- end row -->
                @endforeach
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end Col -->
</div> <!-- end row-->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Faktur Penjualan Terbaru</h4>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20px;">
                                    <div class="form-check font-size-16">
                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                        <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                    </div>
                                </th>
                                <th>Nomor Faktur</th>
                                <th>Nama Outlet</th>
                                <th>Tanggal</th>
                                <th>Tanggal JT</th>
                                <th>Cara Bayar</th>
                                <th>Total Harga</th>
                                <th>Lihat Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fakturPenjualanTerbaru as $faktur)
                            <tr>
                                <td>
                                    <div class="form-check font-size-16">
                                        <input type="checkbox" class="form-check-input"
                                            id="customCheck{{ $faktur->id_faktur }}">
                                        <label class="form-check-label"
                                            for="customCheck{{ $faktur->id_faktur }}">&nbsp;</label>
                                    </div>
                                </td>
                                <td>{{ $faktur->nomor_bukti }}</td>
                                <td>{{ $faktur->outlet->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($faktur->tanggal_buat)->format('d-m-Y') }}</td>
                                <td>{{ $faktur->tanggal_jatuh_tempo ?
                                    \Carbon\Carbon::parse($faktur->tanggal_jatuh_tempo)->format('d-m-Y') : '-' }}</td>
                                <td>
                                    @if($faktur->cara_pembayaran == 'CASH')
                                    <span
                                        class="badge rounded-pill bg-success-subtle text-success font-size-12">CASH</span>
                                    @elseif($faktur->cara_pembayaran == 'CREDIT')
                                    <span
                                        class="badge rounded-pill bg-danger-subtle text-danger font-size-12">CREDIT</span>
                                    @endif
                                </td>
                                <td>Rp {{ number_format($faktur->grand_total, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm"
                                        onclick="showDetail('{{ route('faktur.showDetail', $faktur->id_faktur) }}')">Lihat
                                        Detail</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada transaksi terbaru</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- end table-responsive -->
            </div>
        </div>
    </div>
</div>
<!-- end row -->

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Faktur Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>


@endsection
@section('script')
<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>
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
                // Load the response HTML into the modal body
                $('#modalContent').html(response.html);
            },
            error: function(xhr) {
                $('#modalContent').html('<p>Failed to load data.</p>');
            }
        });
    }
</script>

@endsection