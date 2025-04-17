<?php

namespace App\Exports;

use App\Models\FakturPenjualan; // Pastikan mengimpor FakturPenjualan
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FakturExport implements FromView, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $fakturPenjualan;

    public function __construct(FakturPenjualan $fakturPenjualan)
    {
        $this->fakturPenjualan = $fakturPenjualan;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        return view('faktur_penjualan.export', [
            'faktur' => $this->fakturPenjualan
        ]);
    }

    public function title(): string
    {
        return 'Faktur Penjualan';
    }

    public function headings(): array
    {
        return [
            'Nama Barang', 'Jumlah', 'Harga', 'Diskon', 'Total',
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]], 
            'A'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],  
            'B'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],  
            'C'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],  
            'D'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],  
            'E'  => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],  
        ];
    }
}

