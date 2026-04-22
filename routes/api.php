<?php

use App\Http\Controllers\Esp32Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('esp32')->group(function () {
    Route::get('/device-mode/{device_uid}', [Esp32Controller::class, 'deviceMode']);

    Route::get('/enrollment/request/{device_uid}', [Esp32Controller::class, 'enrollmentRequest']);
    Route::post('/enrollment/request', [Esp32Controller::class, 'storeEnrollmentRequest']);
    Route::post('/enrollment/confirm', [Esp32Controller::class, 'confirmEnrollment']);

    Route::get('/deletion/request/{device_uid}', [Esp32Controller::class, 'deletionRequest']);
    Route::post('/deletion/request', [Esp32Controller::class, 'storeDeletionRequest']);
    Route::post('/deletion/confirm', [Esp32Controller::class, 'confirmDeletion']);

    Route::post('/attendance', [Esp32Controller::class, 'storeAttendance']);
});
