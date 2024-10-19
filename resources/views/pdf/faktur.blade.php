<!DOCTYPE html>
<html>
<head>
    <title>Faktur Penjualan</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        table { width: 100%;  margin-bottom: 20px; }
        th, td { padding: 2px; text-align: left; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
    </style>
</head>
<body>
    <div>
        <table style="width:100%; border='0';">
            <tr>
              <td style="width:33%; vertical-align: top; padding: 10px;">
                <h5 style="margin-top:0;">CV MITRA DIAN HANANIA</h5>
                  JL. BANTENG <br>
                  KENDARI-SULAWESI TENGGARA 93232 <br>
                  NPWP : 62.816.155.6-811.000 <br>
                  SALESMAN : {{ $faktur->outlet->sales->full_name }} <br>
                  AREA : {{ $faktur->outlet->zona->nama }}
              </td>
              <td style="width:33%; vertical-align: top; padding: 10px; ">
                <h3 style="margin-top:0; text-align: center;">FAKTUR PENJUALAN</h3>
                CARA BAYAR: {{ $faktur->cara_pembayaran }}<br>
                TANGGAL: {{ $faktur->tanggal_buat }}<br>
                JATUH TEMPO: {{ $faktur->tanggal_jatuh_tempo ?? '-' }}
              </td>
              <td style="width:33%; vertical-align: top; padding: 10px;">
                <h5 style="margin-top:0;">NO. BUKTI: {{ $faktur->nomor_bukti }}</h5>
                <p style="margin-bottom:0;">
                  KEPADA PELANGGAN: <br>
                  {{ $faktur->outlet->nama }} <br>
                  {{ $faktur->outlet->alamat }}
                </p>
              </td>
            </tr>
          </table>
        <hr>
        <table style="border-collapse: collapse; border='1';">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA BARANG</th>
                    <th>JUMLAH</th>
                    <th class="text-center">HARGA</th>
                    <th class="text-center">DISKON</th>
                    <th class="text-center">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($faktur->detailFakturPenjualan as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->barang->nama_barang }}</td>
                        <td>{{ $detail->jumlah_formatted }}</td>
                        <td class="text-end">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($detail->diskon, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach              
                <tr>                    
                    <td colspan="5" class="text-end"><strong>GRAND TOTAL</strong></td>
                    <td class="text-end">Rp {{ number_format($faktur->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tbody>        
        </table>
        <br><br><br><br><br><br>
        <table style="width:100%; border='0'; margin-top='5px'" >
            <tr>
                <td class="text-center">(.............................) <br> ADMIN</td>
                <td class="text-center">(.............................) <br> GUDANG</td>
                <td class="text-center">(.............................) <br> PENGIRIM</td>
                <td class="text-center">(.............................) <br> PELANGGAN</td>
            </tr>
        </table>
    </div>
</body>
</html>
