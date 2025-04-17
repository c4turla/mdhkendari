<table>
    <tr>
        <td colspan="6" style="font-weight: bold;">CV MITRA DIAN HANANIA</td>
        <td colspan="4" style="font-weight: bold;">FAKTUR PENJUALAN</td>
        <td colspan="3" style="font-weight: bold;">NO. BUKTI : {{ $faktur->nomor_bukti }}</td>
    </tr>
    <tr>
        <td colspan="6">JL. BANTENG</td>
        <td colspan="4">CARA BAYAR: {{ $faktur->cara_pembayaran }}</td>
        <td colspan="3">KEPADA PELANGGAN: </td>
    </tr>
    <tr>
        <td colspan="6">KENDARI-SULAWESI TENGGARA 93232</td>
        <td colspan="4">TANGGAL: {{ $faktur->tanggal_buat }}</td>
        <td colspan="3">{{ strtoupper($faktur->outlet->nama) }}</td>
    </tr>
    <tr>
        <td colspan="6">NPWP: 62.816.155.6-811.000</td>
        <td colspan="4">JATUH TEMPO: {{ $faktur->tanggal_jatuh_tempo }}</td>
        <td colspan="3">{{ strtoupper($faktur->outlet->alamat) }}</td>
    </tr>
    <tr>
        <td colspan="6">SALESMAN: {{ strtoupper($faktur->outlet->sales->full_name) }}</td>
    </tr>
    <tr>
        <td colspan="6">AREA: {{ strtoupper($faktur->outlet->zona->nama) }}</td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <th>No</th>
        <th colspan="5">KETERANGAN/ ITEM BARANG</th>
        <th colspan="2">JUMLAH</th>
        <th colspan="2">GROSS</th>
        <th colspan="2">DISCOUNT</th>
        <th colspan="2">TOTAL</th>
    </tr>
    @foreach ($faktur->detailBaruFakturPenjualan as $key => $detail)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td colspan="5">{{ $detail->barang->nama_barang }}</td>
            <td colspan="2">{{ $detail->jumlah }} {{ $detail->satuan }}</td>
            <td colspan="2">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
            <td colspan="2">Rp {{ number_format($detail->diskon, 0, ',', '.') }}</td>
            <td colspan="2">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="12" style="text-align: right; font-weight: bold;">GRAND TOTAL</td>
        <td style="text-align: right;">Rp {{ number_format($faktur->grand_total, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td colspan="13"></td>
    </tr>
    <tr>
        <td colspan="13"></td>
    </tr>
    <tr>
        <td colspan="13"></td>
    </tr>
    <tr>
        <td colspan="13"></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">(.................)</td>
        <td colspan="3" style="text-align: center;">(.................)</td>
        <td colspan="3" style="text-align: center;">(.................)</td>
        <td colspan="4" style="text-align: center;">(.................)</td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center;">ADMIN</td>
        <td colspan="3" style="text-align: center;">GUDANG</td>
        <td colspan="3" style="text-align: center;">PENGIRIM</td>
        <td colspan="4" style="text-align: center;">PELANGGAN</td>
    </tr>
</table>
