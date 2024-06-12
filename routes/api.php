<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\BookingController;

Route::apiResource('classrooms', ClassroomController::class);
Route::apiResource('timetables', TimetableController::class);
Route::apiResource('bookings', BookingController::class);

Route::get('classrooms/{id}/timetables', [ClassroomController::class, 'timetables']);
Route::get('classrooms/{id}/availability', [ClassroomController::class, 'availability']);