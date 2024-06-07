<?php

use App\Http\Controllers\MonitorController;
use App\Http\Controllers\NotificationChannelController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectInvitationController;
use App\Http\Controllers\ProjectMemberController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects');
    Route::post('/projects/create', [ProjectController::class, 'store'])->name('projects.create');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/projects/{project}/select', [ProjectController::class, 'select'])->name('projects.select');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::post('/projects/{project}/edit', [ProjectController::class, 'update'])->name('projects.edit');

    Route::prefix('projects/{project}')->group(function () {
        Route::resource('project-members', ProjectMemberController::class)->only(['store']);
        Route::delete('project-members/{user}', [ProjectMemberController::class, 'destroy'])->name('project-members.destroy');
    });

    Route::get('/project-invitations/{projectInvitation}', [ProjectInvitationController::class, 'accept'])
        ->middleware(['signed'])
        ->name('project-invitations.accept');

    Route::delete('/project-invitations/{projectInvitation}', [ProjectInvitationController::class, 'destroy'])
        ->name('project-invitations.destroy');

    Route::get('/monitors', [MonitorController::class, 'index'])->name('monitors.index');
    Route::post('/monitors', [MonitorController::class, 'store'])->name('monitors.store');
    Route::get('/monitors/{monitor}', [MonitorController::class, 'show'])->name('monitors.show');
    Route::get('/monitors/{monitor}/status/{status}', [MonitorController::class, 'updateStatus'])->name('monitors.status');
    Route::patch('/monitors/{monitor}/info', [MonitorController::class, 'updateInfo'])->name('monitors.update-info');
    Route::patch('/monitors/{monitor}/configs', [MonitorController::class, 'updateConfigs'])->name('monitors.update-configs');
    Route::patch('/monitors/{monitor}/notification-channels', [MonitorController::class, 'updateNotificationChannels'])
        ->name('monitors.update-notification-channels');
    Route::delete('/monitors/{monitor}', [MonitorController::class, 'destroy'])->name('monitors.destroy');
    Route::get('/monitors/{monitor}/metrics', [MonitorController::class, 'metrics'])->name('monitors.metrics');

    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');

    Route::get('/notification-channels', [NotificationChannelController::class, 'index'])->name('notification-channels.index');
    Route::post('/notification-channels', [NotificationChannelController::class, 'store'])->name('notification-channels.store');
    Route::get('/notification-channels/{notificationChannel}/verify-email', [NotificationChannelController::class, 'verifyEmail'])
        ->name('notification-channels.verify-email')
        ->middleware('signed');
    Route::get('/notification-channels/{notificationChannel}/resend-email', [NotificationChannelController::class, 'resendEmail'])
        ->name('notification-channels.resend-email')
        ->middleware('throttle:1,1');
    Route::delete('/notification-channels/{notificationChannel}', [NotificationChannelController::class, 'destroy'])
        ->name('notification-channels.destroy');
});

require __DIR__.'/auth.php';
