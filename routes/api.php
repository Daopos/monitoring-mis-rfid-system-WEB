<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventdoController;
use App\Http\Controllers\GateMonitorController;
use App\Http\Controllers\HomeOwnerController;
use App\Http\Controllers\HomeownerNotificationController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentReminderController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/all/entry', [GateMonitorController::class, 'getAllEntryAPI'])->middleware('auth:sanctum');

Route::post('/home-owner/register', [AuthController::class, 'homeOwnerRegister']);
Route::post('/home-owner/login', [AuthController::class, 'homeOwnerLogin']);
Route::post('/home-owner/logout', [AuthController::class, 'homeOwnerLogout'])->middleware('auth:sanctum');

Route::get('/home-owner/message/guard', [MessageController::class, 'getMessageGuardAPI'])->middleware('auth:sanctum');
Route::post('/home-owner/message/guard', [MessageController::class, 'sendMessageGuardAPI'])->middleware('auth:sanctum');

Route::get('/home-owner/message', [MessageController::class, 'getMessageAdminAPI'])->middleware('auth:sanctum');
Route::post('/home-owner/message', [MessageController::class, 'sendMessageAdminAPI'])->middleware('auth:sanctum');

Route::get('/home-owner/profile', [HomeOwnerController::class, 'getProfileAPI'])->middleware('auth:sanctum');
Route::put('/home-owner/profile', [HomeOwnerController::class, 'updateProfileAPI'])->middleware('auth:sanctum');

Route::get('/all/event', [EventdoController::class, 'getEventAPI']);

Route::post('/visitor/rfid/request', [VisitorController::class, 'requestRfid'])->middleware('auth:sanctum');

Route::get('/home-owner/visitors', [VisitorController::class, 'getVisitors'])->middleware('auth:sanctum');


Route::apiResource('vehicles', VehicleController::class)->middleware('auth:sanctum');
Route::get('/vehicle', [VehicleController::class, 'getVehicles'])->middleware('auth:sanctum');
Route::put('/vehicle/{id}', [VehicleController::class, 'update'])->middleware('auth:sanctum');

//visitor
Route::post('/add/visitor', [VisitorController::class, 'createVisitorAPI'])->middleware('auth:sanctum');
Route::get('/visitors', [VisitorController::class, 'getVisitorAPI'])->middleware('auth:sanctum');
Route::put('/visitor/{id}', [VisitorController::class, 'updateVisitorAPI'])->middleware('auth:sanctum');
Route::delete('/visitor/{id}', [VisitorController::class, 'deleteVisitorAPI'])->middleware('auth:sanctum');
Route::get('/visitor/approved/{id}', [VisitorController::class, 'approvedVisitorAPI'])->middleware('auth:sanctum');
Route::get('/visitor/denied/{id}', [VisitorController::class, 'rejectVisitorAPI'])->middleware('auth:sanctum');


//reminder
Route::get('/payment-reminders', [PaymentReminderController::class, 'getHomeownerReminders'])->middleware('auth:sanctum');


//households
Route::post('/households', [HouseholdController::class, 'createMemberAPI'])->middleware('auth:sanctum');
Route::put('/households/{id}', [HouseholdController::class, 'updateMemberAPI'])->middleware('auth:sanctum');
Route::delete('/households/{id}', [HouseholdController::class, 'deleteMemberAPI'])->middleware('auth:sanctum');
Route::get('/households', [HouseholdController::class, 'getMembersAPI'])->middleware('auth:sanctum');


//seen message
Route::post('/messages/mark-as-seen', [MessageController::class, 'markAsSeen'])->middleware('auth:sanctum');

Route::get('/homeowner/notifications', [HomeownerNotificationController::class, 'index'])->middleware('auth:sanctum');
Route::post('/homeowner/notifications/delete/{id}', [HomeownerNotificationController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/homeowner/notifications/seen', [HomeownerNotificationController::class, 'markAsRead'])->middleware('auth:sanctum');

// Password reset routes
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


//offices
Route::get('/officers/all',[AdminController::class, 'getOfficerAPI']);
