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
use Illuminate\Support\Facades\DB;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show.public');
Route::get('/jobs/{job}/apply', [ApplicationController::class, 'create'])->name('applications.create.job');
Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store.public');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Route (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
 Route::get('/users', [UserController::class, 'index'])->name('users.index');
  Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::resource('divisions', DivisionController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('jobs', JobController::class)->except(['show']);
    Route::resource('applications', ApplicationController::class)->except(['create', 'store']);

    Route::resource('interviews', InterviewController::class)->except(['show']);


     // routes/web.php
Route::post('/interviews/{interview}/send-invitation', [InterviewController::class, 'sendInvitation'])
     ->name('interviews.send-invitation');
     
Route::post('/interviews/{date}/notify-security', [InterviewController::class, 'notifySecurity'])
     ->name('interviews.notify-security');
     
     Route::get('/interview/verify/{code}', [InterviewController::class, 'verifyInterview'])
    ->name('interview.verify');
    Route::put('/interviews/{interview}/mark-interviewed', [InterviewController::class, 'markAsInterviewed'])
     ->name('interviews.mark-interviewed');

     // Di dalam group middleware auth
Route::get('/applications/{id}/print', [ApplicationPdfController::class, 'generatePdf'])
    ->name('applications.print');
Route::get('/applications/{application}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');

    // Di dalam group middleware auth
Route::get('/applications/{id}/preview', [ApplicationPdfController::class, 'previewPdf'])
    ->name('applications.preview');
    
Route::get('/applications/{id}/download', [ApplicationPdfController::class, 'downloadPdf'])
    ->name('applications.download');

Route::resource('interview-scores', InterviewScoreController::class);
 //CREATE
Route::get('/interview-scores/create/{interview}', [InterviewScoreController::class, 'create'])
     ->name('interview-scores.create');

// STORE (perbaiki parameter menjadi {interview})
Route::post('/interview-scores/{interview}', [InterviewScoreController::class, 'store'])
     ->name('interview-scores.store');

// UPDATE DECISION
Route::put('/interview-scores/{score}/decision', [InterviewScoreController::class, 'updateDecision'])
     ->name('interview-scores.update-decision');
     

      Route::get('/unscored', [InterviewScoreController::class, 'unscored'])->name('interview-scores.unscored');
    Route::get('/hired', [InterviewScoreController::class, 'hired'])->name('interview-scores.hired');
    Route::get('/unhired', [InterviewScoreController::class, 'unhired'])->name('interview-scores.unhired');
    Route::get('/undecided', [InterviewScoreController::class, 'undecided'])->name('interview-scores.undecided');
// SEND RESULT
Route::post('/interview-scores/{score}/send-result', [InterviewScoreController::class, 'sendInterviewResult'])
     ->name('interview-scores.send-result');

     Route::delete('/interview-scores/{interviewScore}', [InterviewScoreController::class, 'destroy'])
    ->name('interview-scores.destroy');
Route::get('/interview-scores/{interviewScore}', [InterviewScoreController::class, 'show'])
    ->name('interview-scores.show');
    
    // routes/web.php
Route::get('/interview-scores/unhired-detail/{id}', [InterviewScoreController::class, 'unhiredDetail'])
     ->name('interview-scores.unhired-detail');
    // routes/web.php (temporary testing route)
Route::get('/test-wablas', function() {
    $wablas = new App\Services\WablasService();
    return $wablas->sendMessage('6282139385685', 'Pesan test dari Wablas');
});
});