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


Route::middleware(['auth', 'verified', 'role:super_admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('2fa')->name('dashboard');

    // User Management
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

    // Services & Variations
    Route::resource('services', ServiceController::class);
    Route::post('services/{service}/fields', [ServiceController::class, 'storeField'])->name('services.fields.store');
    Route::put('service-fields/{field}', [ServiceController::class, 'updateField'])->name('services.fields.update');
    Route::delete('service-fields/{field}', [ServiceController::class, 'destroyField'])->name('services.fields.destroy');
    Route::post('services/{service}/prices', [ServiceController::class, 'storePrice'])->name('services.prices.store');
    Route::put('service-prices/{price}', [ServiceController::class, 'updatePrice'])->name('services.prices.update');
    Route::delete('service-prices/{price}', [ServiceController::class, 'destroyPrice'])->name('services.prices.destroy');

    Route::prefix('admin/data-variations')->name('admin.data-variations.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DataVariationController::class, 'index'])->name('index');
        Route::get('/{service}', [\App\Http\Controllers\Admin\DataVariationController::class, 'show'])->name('show');
        Route::post('/', [\App\Http\Controllers\Admin\DataVariationController::class, 'store'])->name('store');
        Route::put('/{dataVariation}', [\App\Http\Controllers\Admin\DataVariationController::class, 'update'])->name('update');
        Route::delete('/{dataVariation}', [\App\Http\Controllers\Admin\DataVariationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('admin/sme-data')->name('admin.sme-data.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SmeDataController::class, 'index'])->name('index');
        Route::get('/download-sample', [\App\Http\Controllers\Admin\SmeDataController::class, 'downloadSample'])->name('download-sample');
        Route::post('/import', [\App\Http\Controllers\Admin\SmeDataController::class, 'import'])->name('import');
        Route::delete('/delete-all', [\App\Http\Controllers\Admin\SmeDataController::class, 'deleteAll'])->name('delete-all');
        Route::get('/{network}', [\App\Http\Controllers\Admin\SmeDataController::class, 'show'])->name('show');
        Route::post('/', [\App\Http\Controllers\Admin\SmeDataController::class, 'store'])->name('store');
        Route::put('/{smeData}', [\App\Http\Controllers\Admin\SmeDataController::class, 'update'])->name('update');
        Route::delete('/{smeData}', [\App\Http\Controllers\Admin\SmeDataController::class, 'destroy'])->name('destroy');
    });
    
    Route::get('/refresh-variations', [VariationController::class, 'refresh'])->name('variations.refresh');

    // Wallet & Gateway
    Route::prefix('wallet')->name('admin.wallet.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminWalletController::class, 'index'])->name('index');
        Route::get('/summary', [\App\Http\Controllers\Admin\AdminWalletController::class, 'summary'])->name('summary');
        Route::get('/fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'fundView'])->name('fund.view');
        Route::post('/fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'fund'])->name('fund');
        Route::get('/bulk-fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'bulkFundView'])->name('bulk-fund.view');
        Route::post('/bulk-fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'bulkFund'])->name('bulk-fund');
    });

    Route::prefix('gateway')->name('admin.gateway.')->group(function () {
        Route::get('/balance', [\App\Http\Controllers\Admin\GatewayBalanceController::class, 'index'])->name('balance');
        Route::post('/palmpay-balance', [\App\Http\Controllers\Admin\GatewayBalanceController::class, 'updatePalmpayBalance'])->name('palmpay.update');
    });

    // Agency Services (BVN, NIN, CRM, etc.)
    Route::prefix('bvn-modification')->name('bvnmod.')->group(function () {
        Route::get('/', [\App\Http\Controllers\BVNmodController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\BVNmodController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\BVNmodController::class, 'update'])->name('update');
    });

    Route::prefix('bvn-user')->name('bvnuser.')->group(function () {
        Route::get('/', [BvnUserController::class, 'index'])->name('index');
        Route::get('/{id}', [BvnUserController::class, 'show'])->name('show');
        Route::put('/{id}', [BvnUserController::class, 'update'])->name('update');
    });

    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CRMController::class, 'index'])->name('index');
        Route::get('/export/csv', [\App\Http\Controllers\CRMController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/excel', [\App\Http\Controllers\CRMController::class, 'exportExcel'])->name('export.excel');
        Route::get('/{id}', [\App\Http\Controllers\CRMController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\CRMController::class, 'update'])->name('update');
    });

    Route::prefix('bvn-search')->name('bvn-search.')->group(function () {
        Route::get('/', [\App\Http\Controllers\BvnSearchController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\BvnSearchController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\BvnSearchController::class, 'update'])->name('update');
    });

    Route::prefix('nin-modification')->name('ninmod.')->group(function () {
        Route::get('/', [\App\Http\Controllers\NINmodController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\NINmodController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\NINmodController::class, 'update'])->name('update');
    });

    Route::prefix('nin-ipe')->name('ninipe.')->group(function () {
        Route::get('/', [\App\Http\Controllers\NinIpeController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\NinIpeController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\NinIpeController::class, 'update'])->name('update');
    });

    Route::prefix('validation')->name('validation.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ValidationController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\ValidationController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\ValidationController::class, 'update'])->name('update');
    });

    Route::prefix('bvn-service')->name('bvnservice.')->group(function () {
        Route::get('/', [\App\Http\Controllers\BVNserviceController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\BVNserviceController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\BVNserviceController::class, 'update'])->name('update');
    });

    Route::prefix('verification')->name('verification.')->group(function () {
        Route::get('/', [\App\Http\Controllers\VerificationController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\VerificationController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\VerificationController::class, 'update'])->name('update');
    });

    Route::prefix('vnin-nibss')->name('vnin-nibss.')->group(function () {
        Route::get('/', [\App\Http\Controllers\VninToNibssController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\VninToNibssController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\VninToNibssController::class, 'update'])->name('update');
        Route::post('/', [\App\Http\Controllers\VninToNibssController::class, 'store'])->name('store');
    });

    Route::prefix('tin')->name('tin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TinController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\TinController::class, 'show'])->name('show');
        Route::put('/{id}', [\App\Http\Controllers\TinController::class, 'update'])->name('update');
        Route::get('/{id}/certificate', [\App\Http\Controllers\TinController::class, 'downloadCertificate'])->name('certificate');
    });

    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::post('/enrollments/upload', [EnrollmentController::class, 'upload'])->name('enrollments.upload');
    Route::get('/enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');

    // Notifications
    Route::prefix('notification')->name('admin.notification.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
        Route::post('/send', [\App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('send');
        Route::post('/announcement', [\App\Http\Controllers\Admin\NotificationController::class, 'storeAnnouncement'])->name('store-announcement');
        Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\NotificationController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/search-users', [\App\Http\Controllers\Admin\NotificationController::class, 'searchUsers'])->name('search-users');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('destroy');
    });

    // Support
    Route::prefix('support')->name('admin.support.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminSupportController::class, 'index'])->name('index');
        Route::get('/{reference}/messages', [\App\Http\Controllers\Admin\AdminSupportController::class, 'fetchMessages'])->name('messages');
        Route::get('/{reference}', [\App\Http\Controllers\Admin\AdminSupportController::class, 'show'])->name('show');
        Route::post('/{reference}/reply', [\App\Http\Controllers\Admin\AdminSupportController::class, 'reply'])->name('reply');
        Route::post('/{reference}/typing', [\App\Http\Controllers\Admin\AdminSupportController::class, 'typing'])->name('typing');
        Route::get('/{reference}/updates', [\App\Http\Controllers\Admin\AdminSupportController::class, 'updates'])->name('updates');
        Route::post('/{reference}/close', [\App\Http\Controllers\Admin\AdminSupportController::class, 'close'])->name('close');
    });

    // Transactions & Applications
    Route::prefix('api-applications')->name('admin.api-applications.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ApiApplicationController::class, 'index'])->name('index');
        Route::patch('/{id}/status', [\App\Http\Controllers\Admin\ApiApplicationController::class, 'updateStatus'])->name('updateStatus');
    });

    Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('admin.transactions.index');

    // 2FA Admin Actions
    Route::get('verify/resend', [App\Http\Controllers\Auth\TwoFactorController::class, 'resend'])->name('verify.resend');
    Route::resource('verify', App\Http\Controllers\Auth\TwoFactorController::class)->only(['index', 'store']);
    Route::post('profile/two-factor', [App\Http\Controllers\Auth\TwoFactorController::class, 'toggle'])->name('profile.two-factor.toggle');
});

// Profile Routes (Any Authenticated User)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/profile/pin', [ProfileController::class, 'updatePin'])->name('profile.pin');
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
