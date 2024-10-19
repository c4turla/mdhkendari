<div>
    <div class="row">
        <div class="col-sm-4">
            <div class="text-muted">
                <h5 class="font-size-14 mb-2">CV MITRA DIAN HANANIA</h5>
                <h5 class="font-size-12">JL. BANTENG <br/>
                    KENDARI-SULAWESI TENGGARA 93232 <br/>
                    NPWP : 62.816.155.6-811.000 <br/>
                    SALESMAN : {{ $faktur->outlet->sales->full_name }} <br/>
                    AREA : {{ $faktur->outlet->zona->nama }}
                </h5>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="text-center">
                <h5 class="font-size-16 mb-2"><u>FAKTUR PENJUALAN</u></h5>
            </div>
            <div class="mt-2">
                <h5 class="font-size-12">CARA BAYAR : {{ $faktur->cara_pembayaran }}</h5>
                <h5 class="font-size-12">TANGGAL : {{ $faktur->tanggal_buat }}</h5>
                <h5 class="font-size-12">JATUH TEMPO : {{ $faktur->tanggal_jatuh_tempo ?? '-' }}</h5>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="text-muted">
                <div>
                    <h5 class="font-size-14">NO. BUKTI : {{ $faktur->nomor_bukti }} </h5>
                </div>
                <div class="mt-2">
                    <h5 class="font-size-12">KEPADA PELANGGAN :</h5>
                    <h5 class="font-size-12">{{ $faktur->outlet->nama }}</h5>
                    <h5 class="font-size-12">{{ $faktur->outlet->alamat }}</h5>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <table class="table table-bordered table-striped">
        <thead class="thead-light">
            <tr>
                <th class="font-size-13">Nama Barang</th>
                <th class="font-size-13">Jumlah</th>
                <th class="font-size-13">Harga</th>
                <th class="font-size-13">Diskon</th>
                <th class="font-size-13">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($faktur->detailFakturPenjualan as $detail)
                <tr>
                    <td class="font-size-12">{{ $detail->barang->nama_barang }}</td>
                    <td class="font-size-12">{{ $detail->jumlah_formatted }}</td>
                    <td class="font-size-12 text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="font-size-12 text-end">Rp {{ number_format($detail->diskon, 0, ',', '.') }}</td>
                    <td class="font-size-12 text-end">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-end"><strong>Grand Total</strong></td>
                <td class="font-size-13 text-end">Rp {{ number_format($faktur->grand_total, 0, ',', '.') }}</td>
            </tr>
        </tbody>        
    </table>     
</div>



