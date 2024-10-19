<!DOCTYPE html>

<head>
    <title>Surat Jalan</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .mt-0 {
            margin: 0; /* Removes default margin */
            border: none; /* Removes default border */
            height: 1px; /* Sets the height of the line */
            background-color: #000; /* Sets the color of the line */
            width: 100%; /* Makes the line full width */
        }
    </style>
</head>
@php
    $imagePath = public_path('assets/images/logo.png');
    $imageData = base64_encode(file_get_contents($imagePath));
    $src = 'data:image/png;base64,'.$imageData;
@endphp
<body>
    <table style="border-collapse: collapse; width: 100%;" border="0">
        <tbody>
            <tr>
                <td style="width: 14.0625%;">
                    <strong style="text-align: center;">
                        <img src="{{ $src }}" alt="" width="73" height="43" />
                    </strong>
                </td>
                <td style="width: 85.9375%; text-align: center;">
                    <p><strong>SURAT JALAN</strong><br />Jl. Banteng, Kendari - Sulawesi Tenggara, 93232</p>
                </td>
            </tr>
        </tbody>
    </table>

    <hr class="mt-0">

    <table style="border-collapse: collapse; width: 100%; height: 36px;" border="0">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 12.6736%; height: 18px;">NPWP</td>
                <td style="width: 51.2153%; height: 18px;">: 62.816.155.6-811.00</td>
                <td style="width: 16.3194%; height: 18px;">Salesman</td>
                <td style="width: 19.7917%; height: 18px;">: {{ $suratJalan->sales->full_name }}</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 12.6736%; height: 18px;">&nbsp;</td>
                <td style="width: 51.2153%; height: 18px;">&nbsp;</td>
                <td style="width: 16.3194%; height: 18px;">Nomor</td>
                <td style="width: 19.7917%; height: 18px;">: {{ $suratJalan->no_surat_jalan }}</td>
            </tr>
        </tbody>
    </table>
<br>
    <table style="border-collapse: collapse; width: 100%;" border="1">
        <tbody>
            <tr>
                <td style="width: 6.42361%; text-align: center;"><strong>NO</strong></td>
                <td style="width: 43.5764%; text-align: center;"><strong>ITEM</strong></td>
                <td style="width: 21.1806%; text-align: center;"><strong>JUMLAH</strong></td>
                <td style="width: 28.8194%; text-align: center;"><strong>SATUAN</strong></td>
            </tr>
            @php
            $totalDos = 0;
            $totalPcs = 0;
            @endphp
            @foreach($suratJalan->details as $index => $detail)
            <tr>
                <td style="width: 6%;" class="text-center">{{ $index + 1 }}</td>
                <td style="width: 45%;">{{ $detail->barang->nama_barang }}</td>
                <td style="width: 21.1806%;" class="text-center">
                    @if($detail->jumlah_dos > 0)
                    {{ $detail->jumlah_dos }}
                    @php $totalDos += $detail->jumlah_dos; @endphp
                    @else
                    {{ $detail->jumlah_pcs }}
                    @php $totalPcs += $detail->jumlah_pcs; @endphp
                    @endif
                </td>
                <td style="width: 28.8194%;" class="text-center">
                    @if($detail->jumlah_dos > 0)
                    Dos
                    @elseif($detail->jumlah_pcs > 0)
                    Pcs
                    @else
                    -
                    @endif
                </td>
            </tr>
            @endforeach
            <tr>
                <td style="width: 6.42361%; text-align: center;" colspan="2"><strong>TOTAL</strong></td>
                <td style="width: 21.1806%;" class="text-center">
                    <strong>                
                    @if($totalDos > 0)
                    {{ $totalDos }} 
                    @endif
                    @if($totalDos > 0 && $totalPcs > 0)
                    <br>
                    @endif
                    @if($totalPcs > 0)
                    {{ $totalPcs }} 
                    @endif
                </strong>
                </td>
                <td style="width: 28.8194%;">&nbsp;</td>
            </tr>
        </tbody>
    </table>
</body>

</html>