<div>
    <dl class="row mb-0">
        <dt class="col-sm-3">Nomor Surat Jalan</dt>
        <dd class="col-sm-9"><strong>: {{ $suratJalan->no_surat_jalan }} </strong></dd>
        <dt class="col-sm-3">Salesman</dt>
        <dd class="col-sm-9"><strong>: {{ $suratJalan->sales->full_name }}</strong></dd>
        <dt class="col-sm-3">Keterangan</dt>
        <dd class="col-sm-9">: {{ $suratJalan->keterangan }}</dd>
    </dl>
    <hr>
    <table class="table table-bordered table-striped">
        <thead class="thead-light">
            <tr>
                <th class="font-size-13">No</th>
                <th class="font-size-13">Nama Barang</th>
                <th class="font-size-13">Jumlah</th>
                <th class="font-size-13">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratJalan->details as $index => $detail)
                <tr>
                    <td class="font-size-12">{{ $index + 1 }}</td>
                    <td class="font-size-12">{{ $detail->barang->nama_barang }}</td>
                    <td class="font-size-12">
                        @if($detail->jumlah_dos > 0)
                            {{ $detail->jumlah_dos }}
                        @else
                            {{ $detail->jumlah_pcs }}
                        @endif
                    </td>
                    <td class="font-size-12">
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
        </tbody>        
    </table>     
</div>



