<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Child\Auth\AuthController as ChildAuthController;
use App\Http\Controllers\API\Child\AppUsageController;
use App\Http\Controllers\API\Child\LocationController;
use App\Http\Controllers\API\Child\ChildAppRequestController;

use App\Http\Controllers\API\Parent\Auth\AuthController as ParentAuthController;
use App\Http\Controllers\API\Parent\ChildController; 
use App\Http\Controllers\API\Parent\ChildManagementController;
use App\Http\Controllers\API\Parent\ProfileController as ParentProfileController;
use App\Http\Controllers\API\Parent\DowntimeController;
use App\Http\Controllers\API\Parent\ParentLocationController;
use App\Http\Controllers\Api\ChildAppController;

use App\Http\Controllers\API\AlertController;
use App\Http\Controllers\Api\BabyMonitorController; 
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::any('{any}', function() {
    return response()->json(['status' => 'success', 'message' => 'Rules feature completely removed.'], 200);
})->where('any', '.*rules.*');

Route::prefix('hardware')->group(function () {
    Route::post('/heartbeat', [BabyMonitorController::class, 'heartbeat']);
    Route::post('/crying-status', [BabyMonitorController::class, 'receiveCryAlert']);
    Route::get('/light-status', [BabyMonitorController::class, 'getLightStatus']);
    Route::post('/light-toggle', [BabyMonitorController::class, 'toggleLight']); 
});

Route::post('/internal/log-alert', [AlertController::class, 'store']);

Route::get('/parent/alerts', [AlertController::class, 'index']);

Route::post('/password/forgot', [ForgotPasswordController::class, 'sendOtp']);
Route::post('/password/verify-otp', [ForgotPasswordController::class, 'verifyOtp']);
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword']);

Route::prefix('parent')->group(function () {
    Route::post('/register', [ParentAuthController::class, 'register']);
    Route::post('/login', [ParentAuthController::class, 'login']);

    Route::middleware(['auth:parent'])->group(function () {
        
        Route::prefix('alerts')->group(function () {
            Route::post('/mark-all-read', [AlertController::class, 'markAllReadForChild']);
            Route::post('/{uuid}/read', [AlertController::class, 'markRead']);
            Route::delete('/{uuid}', [AlertController::class, 'destroy']);
        });

        Route::post('/light-toggle', [BabyMonitorController::class, 'toggleLight']);

        Route::prefix('app-requests')->group(function () {
            Route::get('/{child_id}', [ChildManagementController::class, 'getPendingRequests']);
            Route::post('/update', [ChildManagementController::class, 'updateRequestStatus']);
        });
        
        Route::get('me', [ParentAuthController::class, 'me']);
        Route::post('logout', [ParentAuthController::class, 'logout']);

        Route::prefix('profile')->group(function () {
            Route::get('show', [ParentProfileController::class, 'show']);
            Route::put('update', [ParentProfileController::class, 'update']);
            Route::post('change-password', [ParentProfileController::class, 'changePassword']);
        });

        Route::prefix('manage-children')->group(function () {
            Route::get('/', [ChildManagementController::class, 'index']); 
            Route::post('store', [ChildManagementController::class, 'store']);
            Route::get('show/{id}', [ChildManagementController::class, 'show']);
            Route::put('update/{id}', [ChildManagementController::class, 'update']);
            Route::delete('destroy/{id}', [ChildManagementController::class, 'destroy']);
            Route::post('change-password/{id}', [ChildManagementController::class, 'changeChildPassword']);
        });

        Route::prefix('childs')->group(function () {
            Route::get('/', [ChildController::class, 'index']); 
            Route::post('/store', [ChildController::class, 'store']);
            Route::delete('/destroy/{id}', [ChildController::class, 'destroy']);
            Route::get('/show-pairing/{child_id}', [ChildController::class, 'showPairingCode']);
        });

        Route::prefix('downtimes')->group(function () {
            Route::get('/', [DowntimeController::class, 'index']);
            Route::post('store', [DowntimeController::class, 'store']);
            Route::get('show/{uuid}', [DowntimeController::class, 'show']);
            Route::patch('update/{uuid}', [DowntimeController::class, 'update']);
            Route::delete('destroy/{uuid}', [DowntimeController::class, 'destroy']);
            Route::get('get-children', [DowntimeController::class, 'getChildrenForDowntime']);
        });

        Route::get('reports/app-usage/{child_id}', [AppUsageController::class, 'getChildUsageForParent']);
        Route::get('child-device-apps/{child_id?}', [ChildAppController::class, 'index']);
        Route::patch('child-apps/update/{id}', [ChildAppController::class, 'update']);

        Route::prefix('locations')->group(function () {
            Route::get('/', [ParentLocationController::class, 'index']);
            Route::get('show/{child_id}', [ParentLocationController::class, 'show']);
        });
    });
});

Route::middleware(['auth:child'])->post('/parent/usage/sync-bulk', [AppUsageController::class, 'syncBulk']);


Route::prefix('child')->group(function () {
    Route::post('login', [ChildAuthController::class, 'login']);
    Route::post('verify-pairing', [ChildController::class, 'verifyPairing']);

    Route::get('config', [ChildAppController::class, 'getAppConfig']);
    Route::post('sync-all-apps', [ChildAppController::class, 'syncAllApps']);
    Route::post('apps/sync-new', [ChildAppController::class, 'syncAllApps']);
    Route::post('/app-requests/store', [ChildAppRequestController::class, 'store']);

    Route::get('/{id}/blocked-apps', [ChildAppController::class, 'getBlockedPackagesOnly']);
    Route::get('/{id}/allowed-apps', [ChildAppController::class, 'getAllowedApps']);
    Route::get('/{id}/allowed-apps-with-timers', [ChildAppController::class, 'getAppsWithTimers']);
    Route::get('downtime/check/{child_id}', [DowntimeController::class, 'checkStatus']);

    Route::prefix('locations')->group(function () {
        Route::post('store', [LocationController::class, 'store']);
    });

    Route::middleware(['auth:child'])->group(function () {
        Route::get('me', [ChildAuthController::class, 'me']);
        Route::post('logout', [ChildAuthController::class, 'logout']);
        Route::post('sync-apps', [ChildAppController::class, 'syncApps']);
    });
});