<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\ApplicationPdfController;
use App\Http\Controllers\InterviewScoreController;
use App\Http\Controllers\RegionController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show.public');
Route::get('/jobs/{job}/apply', [ApplicationController::class, 'create'])->name('applications.create.job');
Route::post('/applications', [ApplicationController::class, 'store'])
    ->name('applications.store.public')
    ->middleware('web');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('api')->group(function () {
    Route::get('/provinces', [RegionController::class, 'getProvinces']);
    Route::get('/regencies/{provinceId}', [RegionController::class, 'getRegencies']);
    Route::get('/districts/{regencyId}', [RegionController::class, 'getDistricts']);
    Route::get('/villages/{districtId}', [RegionController::class, 'getVillages']);
});


// Dashboard Route (Protected)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Divisions
    Route::resource('divisions', DivisionController::class);
    
    // Employees
    Route::resource('employees', EmployeeController::class);
    
    // Jobs
    Route::resource('jobs', JobController::class)->except(['show']);
    
    // Applications
    Route::resource('applications', ApplicationController::class)->except(['show','create', 'store']);
    
    // Application Lists
    Route::prefix('applications')->group(function () {
        Route::get('/waiting-list-office', [ApplicationController::class, 'waitingListOffice'])
            ->name('applications.waiting-list-office');
        Route::get('/waiting-list-production', [ApplicationController::class, 'waitingListProduction'])
            ->name('applications.waiting-list-production');
        Route::get('/review-list-office', [ApplicationController::class, 'reviewListOffice'])
            ->name('applications.review-list-office');
        Route::get('/review-list-production', [ApplicationController::class, 'reviewListProduction'])
            ->name('applications.review-list-production');
    });

    Route::get('/nextcloud-file/{path}', [ApplicationController::class, 'proxyNextcloudFile'])
    ->where('path', '.*') ->name('nextcloud.file.proxy');

    // PDF Related
    Route::get('/applications/{id}/print', [ApplicationPdfController::class, 'generatePdf'])
        ->name('applications.print');
    Route::get('/applications/{id}/preview', [ApplicationPdfController::class, 'previewPdf'])
        ->name('applications.preview');
    Route::get('/applications/{id}/download', [ApplicationPdfController::class, 'downloadPdf'])
        ->name('applications.download');
    Route::get('/applications/{application}/edit', [ApplicationController::class, 'edit'])
        ->name('applications.edit');

    // Interviews
    Route::resource('interviews', InterviewController::class)->except(['show']);
    Route::post('/interviews/{interview}/send-invitation', [InterviewController::class, 'sendInvitation'])
        ->name('interviews.send-invitation');
    Route::post('/interviews/{date}/notify-security', [InterviewController::class, 'notifySecurity'])
        ->name('interviews.notify-security');
    Route::get('/interview/verify/{code}', [InterviewController::class, 'verifyInterview'])
        ->name('interview.verify');
    Route::put('/interviews/{interview}/mark-interviewed', [InterviewController::class, 'markAsInterviewed'])
        ->name('interviews.mark-interviewed');

    // Interview Scores
    Route::prefix('interview-scores')->group(function () {
        // Office Routes
        Route::get('office/unscored', [InterviewScoreController::class, 'officeUnscored'])
            ->name('interview-scores.office-unscored');
        Route::get('office/undecided', [InterviewScoreController::class, 'officeUndecided'])
            ->name('interview-scores.office-undecided');
        Route::get('office/hired', [InterviewScoreController::class, 'officeHired'])
            ->name('interview-scores.office-hired');
        Route::get('office/unhired', [InterviewScoreController::class, 'officeUnhired'])
            ->name('interview-scores.office-unhired');
        
        // Production Routes
        Route::get('production/unscored', [InterviewScoreController::class, 'productionUnscored'])
            ->name('interview-scores.production-unscored');
        Route::get('production/undecided', [InterviewScoreController::class, 'productionUndecided'])
            ->name('interview-scores.production-undecided');
        Route::get('production/hired', [InterviewScoreController::class, 'productionHired'])
            ->name('interview-scores.production-hired');
        Route::get('production/unhired', [InterviewScoreController::class, 'productionUnhired'])
            ->name('interview-scores.production-unhired');
        
        // Common Routes
        Route::post('store-production', [InterviewScoreController::class, 'storeProduction'])
            ->name('interview-scores.store-production');
        Route::get('create/{interview}', [InterviewScoreController::class, 'create'])
            ->name('interview-scores.create');
        Route::post('{interview}', [InterviewScoreController::class, 'store'])
            ->name('interview-scores.store');
        Route::put('{score}/decision', [InterviewScoreController::class, 'updateDecision'])
            ->name('interview-scores.update-decision');
        Route::post('{score}/send-result', [InterviewScoreController::class, 'sendInterviewResult'])
            ->name('interview-scores.send-result');
        Route::delete('{interviewScore}', [InterviewScoreController::class, 'destroy'])
            ->name('interview-scores.destroy');
        Route::get('{interviewScore}', [InterviewScoreController::class, 'show'])
            ->name('interview-scores.show');
        Route::get('unhired-detail/{id}', [InterviewScoreController::class, 'unhiredDetail'])
            ->name('interview-scores.unhired-detail');
    });

    // Testing Route (can be removed in production)
    Route::get('/test-wablas', function() {
        $wablas = new App\Services\WablasService();
        return $wablas->sendMessage('6282139385685', 'Pesan test dari Wablas');
    });
});