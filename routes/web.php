<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\EventdoController;
use App\Http\Controllers\GateMonitorController;
use App\Http\Controllers\HomeOwnerController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\HouseholdGateMonitorController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OutsiderController;
use App\Http\Controllers\PaymentReminderController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\VisitorGateMonitorController;
use App\Models\VisitorGateMonitor;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AdminController::class, 'login'])->name('admin.loginf');



Route::middleware(['admin.auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'getDashboard'])->name('admin.dashboard');

    Route::get('/home-owner/form', [HomeOwnerController::class, 'registrationForm'])->name('admin.homeownerform');
    Route::post('/home-owner/register', [HomeOwnerController::class, 'register'])->name('admin.homeownerformreg');


    Route::get('/homeownerlist', [HomeOwnerController::class, 'getAllHomeOwner'])->name('admin.homeownerlist');

    Route::get('/entrylist', [GateMonitorController::class, 'getAllEntry'])->name('admin.gatelist');


Route::get('/homeowner/edit/{id}', [HomeOwnerController::class, 'edit'])->name('homeowner.edit');
Route::put('/homeowner/update/{id}', [HomeOwnerController::class, 'update'])->name('homeowner.update');

Route::delete('/homeowner/delete/{id}', [HomeOwnerController::class, 'destroy'])->name('homeowner.delete');
Route::post('/homeowner/confirm/{id}', [HomeOwnerController::class, 'confirm'])->name('homeowner.confirm');



Route::get('/homeowner-pending', [HomeOwnerController::class, 'getHomeOwnerPending'])->name('admin.homeownerpending');

Route::get('/messages', [MessageController::class, 'adminMessageIndex'])->name('admin.messages');;
Route::get('/admin/messages/{homeOwner}', [MessageController::class, 'adminShowMessage'])->name('admin.messages.show');
Route::post('/admin/messages/{homeOwner}', [MessageController::class, 'adminSendMessages'])->name('admin.messages.send');


Route::get('/guard/messages', [MessageController::class, 'guardMessageIndex'])->name('guard.messages');;
Route::get('/guard/messages/{homeOwner}', [MessageController::class, 'guardShowMessage'])->name('guard.messages.show');
Route::post('/guard/messages/{homeOwner}', [MessageController::class, 'guardSendMessages'])->name('guard.messages.send');



Route::resource('eventdos', EventdoController::class);



Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
Route::post('/visitors/{id}/approve', [VisitorController::class, 'approve'])->name('visitors.approve');
Route::post('/visitors/{id}/deny', [VisitorController::class, 'deny'])->name('visitors.deny');


Route::get('blocks', [BlockController::class, 'index'])->name('blocks.index');
Route::get('blocks/create', [BlockController::class, 'create'])->name('blocks.create');
Route::post('blocks', [BlockController::class, 'store'])->name('blocks.store');
Route::get('blocks/{block}/edit', [BlockController::class, 'edit'])->name('blocks.edit');
Route::put('blocks/{block}', [BlockController::class, 'update'])->name('blocks.update');
Route::delete('blocks/{block}', [BlockController::class, 'destroy'])->name('blocks.destroy');




Route::get('/rfid/homeowners', [HomeOwnerController::class, 'rfidlist'])->name('admin.rfidlist');

Route::put('/household/{household}/update-rfid', [HouseholdController::class, 'updateRfid'])->name('household.updateRfid');


Route::get('/admin/outsiders', [OutsiderController::class, 'indexAdmin'])->name('admin.outsiders');
Route::get('/admin/visitors', [VisitorGateMonitorController::class, 'indexAdmin'])->name('admin.visitors');

Route::get('/admin/household/gate-monitors', [HouseholdGateMonitorController::class, 'indexHousehold'])->name('admin.householdentry');


Route::get('/admin/households', [HouseholdController::class, 'adminIndexHousehold'])->name('admin.households');
});

Route::get('/todolist', function () {
    return view('admin.admintodolist');
})->name('admin.admintodolist');

Route::get('/rfidapproval', function () {
    return view('admin.adminrfidapproval');
})->name('admin.rfidapproval');

Route::get('/paymentreminder', function () {
    return view('admin.adminpaymentreminder');
})->name('admin.adminpaymentreminder');

//guard

Route::get('/guard/login', [AdminController::class, 'showGuardLoginForm'])->name('guard.login');
Route::post('/guard/login', [AdminController::class, 'guardlogin'])->name('guard.loginf');


Route::middleware(['guard.auth'])->group(function () {
    Route::get('/guard/dashboard', [AdminController::class, 'getguardDashboard'])->name('guard.dashboard');


    Route::get('/gate-monitors', [GateMonitorController::class, 'index'])->name('gate-monitors.index');
    Route::post('/gate-monitors', [GateMonitorController::class, 'store'])->name('gate-monitors.store');

    Route::get('/guard/entrylist', [GateMonitorController::class, 'getAllEntryGuard'])->name('guard.gatelist');

    Route::get('/guard/homeownerlist', [HomeOwnerController::class, 'getAllHomeOwnerGuard'])->name('guard.homeownerlist');

    Route::get('/guard/visitor/gate-monitors', [VisitorGateMonitorController::class, 'index'])->name('guard.visitorgatelist');


    Route::get('/guard/visitors', [VisitorController::class, 'indexGuard'])->name('guard.visitor');
Route::post('/guard/visitors/{id}/approve', [VisitorController::class, 'approveGuard'])->name('guard.approve');
Route::post('/guard/visitors/{id}/deny', [VisitorController::class, 'denyGuard'])->name('guard.deny');
Route::post('/guard/visitors/{id}/delete', [VisitorController::class, 'deleteVisitorGuard'])->name('guard.delete');
Route::post('/guard/visitor/store', [VisitorController::class, 'storeVisitor'])->name('guard.storeVisitor');
Route::post('/guard/visitors/{id}/return', [VisitorController::class, 'ReturnVisitorGuard'])->name('guard.return');

Route::put('/guard/outsiders/{id}', [OutsiderController::class, 'update'])->name('outsiders.update');
Route::get('/guard/outsiders', [OutsiderController::class, 'index'])->name('outsiders.index');
Route::post('/guard/outsiders', [OutsiderController::class, 'store'])->name('outsiders.store');
Route::get('/guard/outsiders/{id}/edit', [OutsiderController::class, 'edit'])->name('outsiders.edit');
Route::delete('/guard/outsiders/{id}', [OutsiderController::class, 'destroy'])->name('outsiders.destroy');
Route::patch('/outsiders/{id}/out', [OutsiderController::class, 'updateOut'])->name('outsiders.updateOut');


Route::get('/guard/household/gate-monitors', [HouseholdGateMonitorController::class, 'index'])->name('guard.householdentry');

});


Route::get('/treasurer/login', [AdminController::class, 'showTreasurerLoginForm'])->name('treasurer.login');
Route::post('/treasurer/login', [AdminController::class, 'treasurerlogin'])->name('treasurer.loginf');

Route::middleware(['treasurer.auth'])->group(function () {
    Route::get('/treasurer/dashboard', [AdminController::class, 'getTreasurerDashboard'])->name('treasurer.dashboard');

    Route::get('payment_reminders', [PaymentReminderController::class, 'index'])->name('payment_reminders.index');
    Route::get('payment_reminders/create', [PaymentReminderController::class, 'create'])->name('payment_reminders.create');
    Route::post('payment_reminders', [PaymentReminderController::class, 'store'])->name('payment_reminders.store');
    Route::get('payment_reminders/{paymentReminder}/edit', [PaymentReminderController::class, 'edit'])->name('payment_reminders.edit');
    Route::put('payment_reminders/{paymentReminder}', [PaymentReminderController::class, 'update'])->name('payment_reminders.update');
    Route::delete('payment_reminders/{paymentReminder}', [PaymentReminderController::class, 'destroy'])->name('payment_reminders.destroy');
    Route::put('payment_reminders/{paymentReminder}/mark-paid', [PaymentReminderController::class, 'markAsPaid'])->name('payment_reminders.markPaid');

Route::get('/paidlist', [PaymentReminderController::class, 'indexPaid'])->name('treasurer.paidlist');

});


Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
Route::post('/treasurer/logout', [AdminController::class, 'logoutTreasurer'])->name('treasurer.logout');
Route::post('/guard/logout', [AdminController::class, 'logoutGuard'])->name('guard.logout');


Route::get('/guard/generate-pdf', [GateMonitorController::class, 'generatePDF'])->name('guard.generatePdf');
Route::get('/outsiders/pdf', [OutsiderController::class, 'generatePdf'])->name('guard.generateOutsiderPdf');
Route::get('/visitor-gate-entry/pdf', [VisitorGateMonitorController::class, 'generatePdf'])->name('guard.visitorgatelist.pdf');
Route::get('/household-entry/pdf', [HouseholdGateMonitorController::class, 'generatePdf'])->name('guard.householdentry.pdf');
