<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobShiftController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JobOwnerController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('home'))->name('home');
Route::get('/home', fn() => redirect()->route('home'));

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/register',        fn() => view('auth.register'))      ->name('register');
    Route::get('/login',           fn() => view('auth.login'))          ->name('login');
    Route::get('/forgot-password', fn() => view('auth.forgotpassword')) ->name('password.request');

    Route::get('/reset-password', function () {
        return redirect()->route('login')->withErrors([
            'email' => 'You cannot access the password reset page directly.',
        ]);
    });

    Route::get('/reset-password/{token}', function (Request $request, string $token) {
        $email = $request->email;

        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Invalid password reset link.',
            ]);
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($token, $record->token)) {
            return redirect()->route('login')->withErrors([
                'email' => 'Invalid or expired password reset link.',
            ]);
        }

        $expireMinutes = config('auth.passwords.users.expire', 60);
        $isExpired     = \Carbon\Carbon::parse($record->created_at)
                            ->addMinutes($expireMinutes)
                            ->isPast();

        if ($isExpired) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('password.request')->withErrors([
                'email' => 'This password reset link has expired. Please request a new one.',
            ]);
        }

        return view('auth.resetpassword', ['token' => $token, 'email' => $email]);

    })->name('password.reset');

    Route::post('/register',        [UserController::class, 'register'])      ->name('register.post');
    Route::post('/login',           [UserController::class, 'login'])          ->name('login.post');
    Route::post('/forgot-password', [UserController::class, 'sendResetLink']) ->name('password.email');
    Route::post('/reset-password',  [UserController::class, 'resetPassword']) ->name('password.update');

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // Self profile update — strictly self only
    Route::patch('/profile/{id}', [UserController::class, 'update'])->name('profile.update');

    // Smart dashboard redirect
    Route::get('/dashboard', function () {
        return match (Auth::user()->user_type) {
            'Admin'     => redirect()->route('dashboard.admin'),
            'JobOwner'  => redirect()->route('dashboard.jobowner'),
            'JobSeeker' => redirect()->route('dashboard.jobseeker'),
            default     => redirect()->route('login'),
        };
    })->name('dashboard');

    // ── Notifications — all authenticated users ───────────────────
    Route::get('/notifications',             [NotificationController::class, 'index'])        ->name('notifications.index');
    Route::get('/notifications/unread',      [NotificationController::class, 'unread'])       ->name('notifications.unread');
    Route::patch('/notifications/read-all',  [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])   ->name('notifications.read');
    Route::delete('/notifications/{id}',     [NotificationController::class, 'destroy'])      ->name('notifications.destroy');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Admin')->prefix('admin')->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.admin');
        Route::get('/profile',   [AdminController::class, 'profile'])  ->name('admin.profile');

        Route::get('/users',         [UserController::class, 'index'])  ->name('users.index');
        Route::get('/users/{id}',    [UserController::class, 'show'])   ->name('users.show');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/skills',         [SkillController::class, 'index'])  ->name('skills.index');
        Route::post('/skills',        [SkillController::class, 'store'])  ->name('skills.store');
        Route::patch('/skills/{id}',  [SkillController::class, 'update']) ->name('skills.update');
        Route::delete('/skills/{id}', [SkillController::class, 'destroy'])->name('skills.destroy');

        Route::post('/jobs/{id}/approve', [JobController::class, 'approve'])->name('jobs.approve');
        Route::post('/jobs/{id}/reject',  [JobController::class, 'reject']) ->name('jobs.reject');

    });

    /*
    |--------------------------------------------------------------------------
    | Shared Job + Shift Routes — Admin & JobOwner
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Admin,JobOwner')->group(function () {

        Route::get('/jobs',         [JobController::class, 'index'])  ->name('jobs.index');
        Route::get('/jobs/{id}',    [JobController::class, 'show'])   ->name('jobs.show');
        Route::post('/jobs',        [JobController::class, 'store'])  ->name('jobs.store');
        Route::patch('/jobs/{id}',  [JobController::class, 'update']) ->name('jobs.update');
        Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->name('jobs.destroy');

        Route::get('/jobs/{job_id}/shifts',               [JobShiftController::class, 'index'])  ->name('shifts.index');
        Route::post('/jobs/{job_id}/shifts',              [JobShiftController::class, 'store'])  ->name('shifts.store');
        Route::patch('/jobs/{job_id}/shifts/{shift_id}',  [JobShiftController::class, 'update']) ->name('shifts.update');
        Route::delete('/jobs/{job_id}/shifts/{shift_id}', [JobShiftController::class, 'destroy'])->name('shifts.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | JobOwner Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:JobOwner')->group(function () {

        Route::get('/dashboard/jobowner',         [JobOwnerController::class, 'dashboard'])->name('dashboard.jobowner');
        Route::get('/dashboard/jobowner/profile', [JobOwnerController::class, 'profile'])  ->name('jobowner.profile');

        Route::get('/my-jobs/applications',         [JobApplicationController::class, 'ownerIndex'])     ->name('applications.ownerIndex');
        Route::get('/my-jobs/{jobId}/applications', [JobApplicationController::class, 'jobApplications'])->name('applications.jobApplications');
        Route::patch('/applications/{id}/status',   [JobApplicationController::class, 'updateStatus'])   ->name('applications.updateStatus');

    });

    /*
    |--------------------------------------------------------------------------
    | JobSeeker Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:JobSeeker')->group(function () {

        Route::get('/dashboard/jobseeker', fn() => view('dashboard.jobseeker.dashboard'))->name('dashboard.jobseeker');

        Route::post('/availabilities',        [AvailabilityController::class, 'store'])  ->name('availabilities.store');
        Route::delete('/availabilities/{id}', [AvailabilityController::class, 'destroy'])->name('availabilities.destroy');

        Route::post('/skills/sync', [SkillController::class, 'syncSeekerSkills'])->name('skills.sync');

        Route::post('/jobs/{jobId}/apply',        [JobApplicationController::class, 'apply'])          ->name('applications.apply');
        Route::get('/my-applications',            [JobApplicationController::class, 'myApplications']) ->name('applications.mine');
        Route::patch('/applications/{id}/cancel', [JobApplicationController::class, 'cancel'])         ->name('applications.cancel');

    });

});