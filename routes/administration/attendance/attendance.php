<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Attendance\AttendanceController;
use App\Http\Controllers\Administration\Attendance\QrCodeAttendanceController;
use App\Http\Controllers\Administration\Attendance\BarCodeAttendanceController;

/* ==============================================
===============< Attendance Routes >==============
===============================================*/
Route::controller(AttendanceController::class)->prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/all', 'index')->name('index');
    Route::get('/my', 'myAttendances')->name('my');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::post('/clockin', 'clockIn')->name('clockin');
    Route::post('/clockout', 'clockOut')->name('clockout');
    
    Route::get('/show/{attendance}', 'show')->name('show');
    Route::post('/update/{attendance}', 'update')->name('update');
    
    Route::get('/export', 'export')->name('export');
});

Route::controller(QrCodeAttendanceController::class)->prefix('attendance/qrcode')->name('attendance.qrcode.')->group(function () {
    Route::get('/scan', 'scanner')->name('scanner');
    Route::get('/scan/{scanner_id}/{qr_code}/{type?}', 'scanQrCode')->name('scan');
});

Route::controller(BarCodeAttendanceController::class)->prefix('attendance/barcode')->name('attendance.barcode.')->group(function () {
    Route::get('/scan', 'scanner')->name('scanner');
    Route::post('/scan/{scanner_id}', 'scanBarCode')->name('scan');
});