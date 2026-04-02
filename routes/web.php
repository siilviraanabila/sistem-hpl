<?php

use App\Http\Controllers\PimpinanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\AdminController;


Route::get('/', [SesiController::class, 'welcome'])->name('welcome');
Route::post('/logout', [SesiController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/login', [SesiController::class, 'getLogin'])->name('getLogin');
    Route::post('/login', [SesiController::class, 'login'])->name('login');

    Route::get('/register', [SesiController::class, 'getRegister'])->name('register.form');
    Route::post('/register', [SesiController::class, 'register'])->name('register');
});
Route::middleware(['akses:admin', 'prevent-back-history'])->group(function () { 
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/shm', [AdminController::class, 'getShm'])->name('getShm');
    Route::post('/admin/storeShm', [AdminController::class, 'storeShm'])->name('storeShm');
    Route::post('/admin/shm/dokumen/store', [AdminController::class, 'storeDokumenShm'])->name('storeDokumenShm');
    Route::get('/admin/shm/editShm/{shm_id}', [AdminController::class, 'editShm'])->name('editShm');
    Route::put('/admin/shm/updateShm/{shm_id}', [AdminController::class, 'updateShm'])->name('updateShm');
    Route::delete('/admin/shm/deleteShm/{shm_id}', [AdminController::class, 'deleteShm'])->name('deleteShm');
    Route::put('/admin/shm/dokumen/{id}', [AdminController::class, 'updateDokumenShm'])->name('updateDokumenShm');
    Route::delete('/admin/shm/dokumen/{id}', [AdminController::class, 'deleteDokumenShm'])->name('deleteDokumenShm');
    Route::post('/admin/shm/cek-kawasan', [AdminController::class, 'cekKawasan'])->name('cekKawasan');

    Route::get('/admin/hpl', [AdminController::class, 'getHpl'])->name('getHpl');
    Route::post('/admin/storeHpl', [AdminController::class, 'storeHpl'])->name('storeHpl');
    Route::get('/admin/hpl/editHpl/{hpl_id}', [AdminController::class, 'editHpl'])->name('editHpl');
    Route::put('/admin/hpl/updateHpl/{hpl_id}', [AdminController::class, 'updateHpl'])->name('updateHpl');
    Route::delete('/admin/hpl/deleteHpl/{hpl_id}', [AdminController::class, 'deleteHpl'])->name('deleteHpl');
    Route::put('/admin/hpl/dokumen/{id}', [AdminController::class, 'updateDokumenHpl'])->name('updateDokumenHpl');
    Route::delete('/admin/hpl/dokumen/{id}', [AdminController::class, 'deleteDokumenHpl'])->name('deleteDokumenHpl');
    Route::post('/admin/hpl/dokumen/store', [AdminController::class, 'storeDokumenHpl'])->name('storeDokumenHpl');
    Route::post('/admin/hpl/check-kawasan', [AdminController::class, 'checkKawasan'])->name('checkKawasan');

    Route::get('/admin/permasalahanLahan', [AdminController::class, 'getPermasalahanLahan'])->name('getPermasalahanLahan');
    Route::post('/admin/storePl', [AdminController::class, 'storePl'])->name('storePl');
    Route::get('/admin/permasalahanLahan/editPl/{pl_id}', [AdminController::class, 'editPl'])->name('editPl');
    Route::put('/admin/permasalahanLahan/updatePl/{pl_id}', [AdminController::class, 'updatePl'])->name('updatePl');
    Route::delete('/admin/permasalahanLahan/deletePl/kawasan/{id}', [AdminController::class, 'deletePl'])->name('deletePl');
    Route::put('/admin/permasalahanLahan/dokumen/{id}', [AdminController::class, 'updateDokumenPl'])->name('updateDokumenPl');
    Route::delete('/admin/permasalahanLahan/dokumen/{id}', [AdminController::class, 'deleteDokumenPl'])->name('deleteDokumenPl');
    Route::post('/admin/permasalahanLahan/dokumen/store', [AdminController::class, 'storeDokumenPl'])->name('storeDokumenPl');

    Route::get('/get-kabupaten/{provinsi_id}', [AdminController::class, 'getKabupaten'])->name('getKabupaten');
    Route::get('/admin/dashboard/pdf', [AdminController::class,'dashboardPdf'])->name('admin.dashboard.pdf');

});
Route::middleware(['akses:pimpinan', 'prevent-back-history'])->group(function () { 
    Route::get('/pimpinan/dashboard', [PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
});