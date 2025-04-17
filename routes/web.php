<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ZonaController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HargaBarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\FakturPenjualanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\BarangReturnController;
use App\Exports\FakturExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\FakturPenjualan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root']);

Route::get('/index', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/faktur-penjualan/{id}/show', [HomeController::class, 'showDetail'])->name('faktur.showDetail');

Route::resource('user', UserController::class);
Route::resource('zonas', ZonaController::class);
Route::resource('outlets', OutletController::class);
Route::resource('barang', BarangController::class);
Route::resource('harga_barang', HargaBarangController::class);
Route::resource('barangmasuk', BarangMasukController::class);
Route::resource('faktur_penjualan', FakturPenjualanController::class);
Route::resource('surat_jalan', SuratJalanController::class);
Route::resource('barang_return', BarangReturnController::class);
Route::get('barang_return/getFakturDetails/{id}', [BarangReturnController::class, 'getFakturDetails']);
Route::get('/faktur-penjualan/{id}/cetak', [FakturPenjualanController::class, 'cetakFaktur'])->name('faktur.cetak');
Route::get('/faktur-penjualan/{id}/download', [FakturPenjualanController::class, 'downloadFaktur'])->name('faktur.download');
Route::get('/surat-jalan/{id}/cetak', [SuratJalanController::class, 'cetakSuratJalan'])->name('surat_jalan.cetak');
Route::get('/faktur/{id}/download-excel', function ($id) {
    $faktur = FakturPenjualan::findOrFail($id);  // Gunakan FakturPenjualan, bukan Faktur
    return Excel::download(new FakturExport($faktur), 'faktur_penjualan_' . $faktur->nomor_bukti . '.xlsx');
})->name('faktur.download.excel');

Route::get('/get-zona-barang', [FakturPenjualanController::class, 'getZonaBarang']);
Route::get('/get-harga-barang', [FakturPenjualanController::class, 'getHargaBarang']);
Route::get('/get-satuan', [FakturPenjualanController::class, 'getHargaDanSatuan']);
//Language Translation

Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::post('/formsubmit', [App\Http\Controllers\HomeController::class, 'FormSubmit'])->name('FormSubmit');
