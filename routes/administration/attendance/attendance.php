<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administration\Attendance\AttendanceController;
use App\Http\Controllers\Administration\Attendance\AttendanceImportController;
use App\Http\Controllers\Administration\Attendance\QrCodeAttendanceController;
use App\Http\Controllers\Administration\Attendance\BarCodeAttendanceController;
use App\Http\Controllers\Administration\Attendance\Issue\AttendanceIssueController;

/* ==============================================
===============< Attendance Routes >==============
===============================================*/
Route::controller(AttendanceController::class)->prefix('attendance')->name('attendance.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Attendance Everything');
    Route::get('/my', 'myAttendances')->name('my')->can('Attendance Read');
    Route::get('/create', 'create')->name('create')->can('Attendance Everything');
    Route::post('/store', 'store')->name('store')->can('Attendance Everything');
    Route::post('/clockin', 'clockIn')->name('clockin')->can('Attendance Read');
    Route::post('/clockout', 'clockOut')->name('clockout')->can('Attendance Read');
    
    Route::get('/show/{attendance}', 'show')->name('show')->can('Attendance Read');
    Route::post('/update/{attendance}', 'update')->name('update')->can('Attendance Update');
    
    Route::get('/export', 'export')->name('export')->can('Attendance Everything');
});

Route::controller(QrCodeAttendanceController::class)->prefix('attendance/qrcode')->name('attendance.qrcode.')->group(function () {
    Route::get('/scan', 'scanner')->name('scanner')->can('Attendance Create');
    Route::get('/scan/{scanner_id}/{qr_code}/{type?}', 'scanQrCode')->name('scan')->can('Attendance Create');
});

Route::controller(BarCodeAttendanceController::class)->prefix('attendance/barcode')->name('attendance.barcode.')->group(function () {
    Route::get('/scan', 'scanner')->name('scanner')->can('Attendance Create');
    Route::post('/scan/{scanner_id}', 'scanBarCode')->name('scan')->can('Attendance Create');
});

Route::controller(AttendanceImportController::class)->prefix('attendance/create/import')->name('attendance.import.')->group(function () {
    Route::get('/', 'index')->name('index')->can('Attendance Create');
    Route::post('/upload', 'upload')->name('upload')->can('Attendance Create');
});

Route::controller(AttendanceIssueController::class)->prefix('attendance/issue')->name('attendance.issue.')->group(function () {
    Route::get('/all', 'index')->name('index')->can('Attendance Read');
    Route::get('/my', 'my')->name('my')->can('Attendance Read');
    Route::get('/create', 'create')->name('create')->can('Attendance Read');

    Route::post('/store', 'store')->name('store')->can('Attendance Read');
    Route::put('/update/{issue}', 'update')->name('update')->can('Attendance Update');

    Route::get('/show/{issue}', 'show')->name('show')->can('Attendance Read');
    
    Route::get('/destroy/{issue}', 'destroy')->name('destroy')->can('Attendance Update');
});