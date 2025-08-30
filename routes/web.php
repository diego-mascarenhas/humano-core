<?php

use Illuminate\Support\Facades\Route;
use Idoneo\HumanoCore\Http\Controllers\UserController;
use Idoneo\HumanoCore\Http\Controllers\CategoryController;
use Idoneo\HumanoCore\Http\Controllers\ActivityLogController;
use Idoneo\HumanoCore\Http\Controllers\DashboardController;
use Idoneo\HumanoCore\Http\Controllers\TeamSettingController;

/*
|--------------------------------------------------------------------------
| Core Web Routes
|--------------------------------------------------------------------------
*/

// SIMPLE TEST ROUTE - NO MIDDLEWARE (under web for session)
Route::middleware('web')->get('/test-package', function() {
    return '<h1>🎉 Package Works!</h1><p>idoneo/humano-core is loading routes correctly</p><p>Time: ' . now() . '</p>';
})->name('test.package');

// TEST WITH SIMPLE MIDDLEWARE (LIKE MAIN APP) - ensure web first
Route::middleware(['web','auth'])->get('/test-middleware', function() {
    return '<h1>🔒 Simple Auth Works!</h1><p>User: ' . auth()->user()->name . '</p><p>Team: ' . auth()->user()->currentTeam->name . '</p><p>Time: ' . now() . '</p>';
})->name('test.middleware');

// TEST WITH SIMPLE MIDDLEWARE + TEAM PARAMETER - ensure web first
Route::middleware(['web','auth'])->get('/test-team/{team}', function($team) {
    return '<h1>👥 Simple Auth + Team Works!</h1><p>Team ID from URL: ' . $team . '</p><p>User: ' . auth()->user()->name . '</p><p>Current Team: ' . auth()->user()->currentTeam->name . '</p><p>Time: ' . now() . '</p>';
})->name('test.team');

// Ensure web middleware so session-based auth works consistently
Route::middleware(['web','auth'])->group(function () {

        // TEMPORARILY DISABLED - Controllers have syntax errors
    // Users
    // Route::resource('users', UserController::class);

    // Categories
    // Route::resource('categories', CategoryController::class);
    // Route::post('/categories/order', [CategoryController::class, 'updateOrder'])->name('categories.order');
    // Route::get('/categories/{id}/items', [CategoryController::class, 'showItems'])->name('categories.items');

    // Activity Log
    // Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    // Route::get('/activity-log/{id}', [ActivityLogController::class, 'show'])->name('activity-log.show');

    // Dashboard Analytics
    // Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');

    // Team Settings
    Route::get('/team/{team}/settings', [TeamSettingController::class, 'index'])->name('team-settings.index');
    Route::get('/team/{team}/settings/{group?}', [TeamSettingController::class, 'edit'])->name('team-settings.edit');
    Route::put('/team/{team}/settings', [TeamSettingController::class, 'update'])->name('team-settings.update');
    Route::post('/team/{team}/test-smtp', [TeamSettingController::class, 'testSmtpConnection'])->name('team-settings.test-smtp');
    Route::post('/team/{team}/test-imap', [TeamSettingController::class, 'testImapConnection'])->name('team-settings.test-imap');
    Route::post('/team/{team}/test-stripe', [TeamSettingController::class, 'testStripeConnection'])->name('team-settings.test-stripe');
    Route::post('/team/{team}/test-twilio', [TeamSettingController::class, 'testTwilioConnection'])->name('team-settings.test-twilio');

    // Team Valorations
    Route::get('/team/{team}/valorations', [TeamSettingController::class, 'valorations'])->name('team-settings.valorations');
    Route::post('/team/{team}/valorations', [TeamSettingController::class, 'storeValoration'])->name('team-settings.valorations.store');
    Route::put('/team/{team}/valorations/{valoration}', [TeamSettingController::class, 'updateValoration'])->name('team-settings.valorations.update');
    Route::delete('/team/{team}/valorations/{valoration}', [TeamSettingController::class, 'destroyValoration'])->name('team-settings.valorations.destroy');

    // Team API Tokens
    Route::get('/team/{team}/api-tokens', [TeamSettingController::class, 'apiTokens'])->name('team-settings.api-tokens');
    Route::post('/team/{team}/api-tokens/generate', [TeamSettingController::class, 'generateApiToken'])->name('team-settings.generate-api-token');
    Route::delete('/team/{team}/api-tokens/revoke', [TeamSettingController::class, 'revokeApiToken'])->name('team-settings.revoke-api-token');

    // Custom Translations
    Route::get('/team/{team}/custom-translations', [TeamSettingController::class, 'customTranslations'])->name('team-settings.custom-translations');
    Route::post('/team/{team}/custom-translations', [TeamSettingController::class, 'storeCustomTranslation'])->name('team-settings.custom-translations.store');
    Route::put('/team/{team}/custom-translations/{translation}', [TeamSettingController::class, 'updateCustomTranslation'])->name('team-settings.custom-translations.update');
    Route::delete('/team/{team}/custom-translations/{translation}', [TeamSettingController::class, 'destroyCustomTranslation'])->name('team-settings.custom-translations.destroy');
    Route::post('/team/{team}/custom-translations/import', [TeamSettingController::class, 'importCustomTranslations'])->name('team-settings.custom-translations.import');

});
