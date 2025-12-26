<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\BVNmodController;
use App\Http\Controllers\BvnUserController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'role:super_admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/profile/pin', [ProfileController::class, 'updatePin'])->name('profile.pin');
});

//Bvn report upload
Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
Route::post('/enrollments/upload', [EnrollmentController::class, 'upload'])->name('enrollments.upload');

Route::get('/enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');

// vtpass variations refresh
Route::get('/refresh-variations', [VariationController::class, 'refresh'])->name('variations.refresh');

// BVN Modification Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bvn-modification', [\App\Http\Controllers\BVNmodController::class, 'index'])->name('bvnmod.index');
    Route::get('/bvn-modification/{id}', [\App\Http\Controllers\BVNmodController::class, 'show'])->name('bvnmod.show');
    Route::put('/bvn-modification/{id}', [\App\Http\Controllers\BVNmodController::class, 'update'])->name('bvnmod.update');
});

// BVN User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bvn-user', [BvnUserController::class, 'index'])->name('bvnuser.index');
    Route::get('/bvn-user/{id}', [BvnUserController::class, 'show'])->name('bvnuser.show');
    Route::put('/bvn-user/{id}', [BvnUserController::class, 'update'])->name('bvnuser.update');
});

// CRM Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/crm', [\App\Http\Controllers\CRMController::class, 'index'])->name('crm.index');
    Route::get('/crm/export/csv', [\App\Http\Controllers\CRMController::class, 'exportCsv'])->name('crm.export.csv');
    Route::get('/crm/export/excel', [\App\Http\Controllers\CRMController::class, 'exportExcel'])->name('crm.export.excel');
    Route::get('/crm/{id}', [\App\Http\Controllers\CRMController::class, 'show'])->name('crm.show');
    Route::put('/crm/{id}', [\App\Http\Controllers\CRMController::class, 'update'])->name('crm.update');
});

// BVN Search Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bvn-search', [\App\Http\Controllers\BvnSearchController::class, 'index'])->name('bvn-search.index');
    Route::get('/bvn-search/{id}', [\App\Http\Controllers\BvnSearchController::class, 'show'])->name('bvn-search.show');
    Route::put('/bvn-search/{id}', [\App\Http\Controllers\BvnSearchController::class, 'update'])->name('bvn-search.update');
});

// NIN Modification Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/nin-modification', [\App\Http\Controllers\NINmodController::class, 'index'])->name('ninmod.index');
    Route::get('/nin-modification/{id}', [\App\Http\Controllers\NINmodController::class, 'show'])->name('ninmod.show');
    Route::put('/nin-modification/{id}', [\App\Http\Controllers\NINmodController::class, 'update'])->name('ninmod.update');
});

// NIN IPE Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/nin-ipe', [\App\Http\Controllers\NinIpeController::class, 'index'])->name('ninipe.index');
    Route::get('/nin-ipe/{id}', [\App\Http\Controllers\NinIpeController::class, 'show'])->name('ninipe.show');
    Route::put('/nin-ipe/{id}', [\App\Http\Controllers\NinIpeController::class, 'update'])->name('ninipe.update');
});

// Validation Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/validation', [\App\Http\Controllers\ValidationController::class, 'index'])->name('validation.index');
    Route::get('/validation/{id}', [\App\Http\Controllers\ValidationController::class, 'show'])->name('validation.show');
    Route::put('/validation/{id}', [\App\Http\Controllers\ValidationController::class, 'update'])->name('validation.update');
});

// BVN Service Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bvn-service', [\App\Http\Controllers\BVNserviceController::class, 'index'])->name('bvnservice.index');
    Route::get('/bvn-service/{id}', [\App\Http\Controllers\BVNserviceController::class, 'show'])->name('bvnservice.show');
    Route::put('/bvn-service/{id}', [\App\Http\Controllers\BVNserviceController::class, 'update'])->name('bvnservice.update');
});

// Verification Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/verification', [\App\Http\Controllers\VerificationController::class, 'index'])->name('verification.index');
    Route::get('/verification/{id}', [\App\Http\Controllers\VerificationController::class, 'show'])->name('verification.show');
    Route::put('/verification/{id}', [\App\Http\Controllers\VerificationController::class, 'update'])->name('verification.update');
});

// VNIN to NIBSS Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/vnin-nibss', [\App\Http\Controllers\VninToNibssController::class, 'index'])->name('vnin-nibss.index');
    Route::get('/vnin-nibss/{id}', [\App\Http\Controllers\VninToNibssController::class, 'show'])->name('vnin-nibss.show');
    Route::put('/vnin-nibss/{id}', [\App\Http\Controllers\VninToNibssController::class, 'update'])->name('vnin-nibss.update');
    Route::post('/vnin-nibss', [\App\Http\Controllers\VninToNibssController::class, 'store'])->name('vnin-nibss.store');
});

// TIN Services Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tin', [\App\Http\Controllers\TinController::class, 'index'])->name('tin.index');
    Route::get('/tin/{id}', [\App\Http\Controllers\TinController::class, 'show'])->name('tin.show');
    Route::put('/tin/{id}', [\App\Http\Controllers\TinController::class, 'update'])->name('tin.update');
});



    Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('services', ServiceController::class);
    
    // Field Routes
    Route::post('services/{service}/fields', [ServiceController::class, 'storeField'])->name('services.fields.store');
    Route::put('service-fields/{field}', [ServiceController::class, 'updateField'])->name('services.fields.update');
    Route::delete('service-fields/{field}', [ServiceController::class, 'destroyField'])->name('services.fields.destroy');

    // Price Routes
    Route::post('services/{service}/prices', [ServiceController::class, 'storePrice'])->name('services.prices.store');
    Route::put('service-prices/{price}', [ServiceController::class, 'updatePrice'])->name('services.prices.update');
    Route::delete('service-prices/{price}', [ServiceController::class, 'destroyPrice'])->name('services.prices.destroy');

    // User Management Routes
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::post('/block-ip', [UserManagementController::class, 'blockIp'])->name('block-ip');
        Route::delete('/unblock-ip/{blockedIp}', [UserManagementController::class, 'unblockIp'])->name('unblock-ip');
        Route::get('/download-sample', [UserManagementController::class, 'downloadSample'])->name('download-sample');
        Route::post('/import', [UserManagementController::class, 'import'])->name('import');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/status', [UserManagementController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{user}/role', [UserManagementController::class, 'updateRole'])->name('update-role');
        Route::patch('/{user}/limit', [UserManagementController::class, 'updateLimit'])->name('update-limit');
        Route::patch('/{user}/verify-email', [UserManagementController::class, 'verifyEmail'])->name('verify-email');
    });

    // Wallet Management Routes
    Route::prefix('wallet')->name('admin.wallet.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminWalletController::class, 'index'])->name('index');
        Route::get('/fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'fundView'])->name('fund.view');
        Route::post('/fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'fund'])->name('fund');
        Route::get('/bulk-fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'bulkFundView'])->name('bulk-fund.view');
        Route::post('/bulk-fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'bulkFund'])->name('bulk-fund');
    });

    // Gateway Balance Management Routes
    Route::prefix('gateway')->name('admin.gateway.')->group(function () {
        Route::get('/balance', [\App\Http\Controllers\Admin\GatewayBalanceController::class, 'index'])->name('balance');
        Route::post('/palmpay-balance', [\App\Http\Controllers\Admin\GatewayBalanceController::class, 'updatePalmpayBalance'])->name('palmpay.update');
    });

    Route::middleware('auth')->group(function () {
    Route::get('/bvnmod', [BVNmodController::class, 'index'])->name('bvnmod.index');
    Route::get('/bvnmod/view/{id}', [BVNmodController::class, 'show'])->name('bvnmod.show');
    Route::put('/bvnmod/view/{id}', [BVNmodController::class, 'update'])->name('bvnmod.update');
});

// Notification Routes
Route::prefix('admin/notification')->name('admin.notification.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
    Route::post('/send', [\App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('send');
    Route::get('/search-users', [\App\Http\Controllers\Admin\NotificationController::class, 'searchUsers'])->name('search-users');
});

// Admin Support Routes
Route::prefix('admin/support')->name('admin.support.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminSupportController::class, 'index'])->name('index');
    Route::get('/{reference}/messages', [\App\Http\Controllers\Admin\AdminSupportController::class, 'fetchMessages'])->name('messages');
    Route::get('/{reference}', [\App\Http\Controllers\Admin\AdminSupportController::class, 'show'])->name('show');
    Route::post('/{reference}/reply', [\App\Http\Controllers\Admin\AdminSupportController::class, 'reply'])->name('reply');
    Route::post('/{reference}/typing', [\App\Http\Controllers\Admin\AdminSupportController::class, 'typing'])->name('typing');
    Route::get('/{reference}/updates', [\App\Http\Controllers\Admin\AdminSupportController::class, 'updates'])->name('updates');
    Route::post('/{reference}/close', [\App\Http\Controllers\Admin\AdminSupportController::class, 'close'])->name('close');
});



    // Transaction History
    Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('admin.transactions.index');
   
});

// Test Routes for Notification (Temporary)
Route::get('/test-notification-payment', function () {
    $data = [
        'type' => 'Debit',
        'amount' => 5000.00,
        'ref' => 'REF123456789',
        'bankName' => 'Access Bank'
    ];
    return new App\Mail\SendNotification('Transaction Alert', $data);
});

Route::get('/test-notification-generic', function () {
    return new App\Mail\SendNotification('Welcome', 'Welcome to our platform!');
});

require __DIR__.'/auth.php';
