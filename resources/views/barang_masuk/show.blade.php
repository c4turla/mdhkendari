<div>
    <dl class="row mb-0">
        <dt class="col-sm-3">Kode Barang Masuk</dt>
        <dd class="col-sm-9"><strong>: {{ $barangMasuk->kode_barang_masuk }} </strong></dd>
        <dt class="col-sm-3">Tanggal Masuk</dt>
        <dd class="col-sm-9"><strong>: {{ $barangMasuk->tanggal_masuk }}</strong></dd>
        <dt class="col-sm-3">Keterangan</dt>
        <dd class="col-sm-9">: {{ $barangMasuk->keterangan }}</dd>
    </dl>

    <hr>

    <h5 class="text-muted">Detail Barang</h5>

    <table class="table table-bordered table-striped">
        <thead class="thead-light">
            <tr>
                <th>Nama Barang</th>
                <th>Jumlah Dos</th>
                <th>Jumlah Pcs</th>
                <th>Jumlah Lainnya</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangMasuk->detailBarangMasuk as $detail)
                <tr>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td>{{ $detail->jumlah_dos }} Dos</td>
                    <td>{{ $detail->jumlah_pcs }} Pcs</td>
                    <td>{{ $detail->jumlah_lainnya }} {{ $detail->satuan_lainnya }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
