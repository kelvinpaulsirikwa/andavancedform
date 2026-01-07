<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrainingNeedsAssessmentController;
use App\Http\Controllers\AdminPages\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/questions', [DashboardController::class, 'questions'])->name('dashboard.questions');
    Route::get('/dashboard/responses', [DashboardController::class, 'responses'])->name('dashboard.responses');
    Route::get('/dashboard/report', [DashboardController::class, 'report'])->name('dashboard.report');
    Route::get('/dashboard/report/export', [DashboardController::class, 'exportReport'])->name('dashboard.report.export');
    Route::get('/dashboard/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard.questions');
    })->name('dashboard');
    Route::get('/dashboard/response/{id}', [DashboardController::class, 'showResponse'])->name('dashboard.response');
    Route::post('/dashboard/response/{id}/mark-read', [DashboardController::class, 'markAsRead'])->name('dashboard.response.mark-read');
    Route::delete('/dashboard/response/{id}', [DashboardController::class, 'deleteResponse'])->name('dashboard.response.delete');
    
    // Filter routes
    Route::get('/dashboard/filter/gender/{gender}', [DashboardController::class, 'filterByGender'])->name('dashboard.filter.gender');
    Route::get('/dashboard/filter/age/{range}', [DashboardController::class, 'filterByAge'])->name('dashboard.filter.age');
    Route::get('/dashboard/filter/workstation/{workstation}', [DashboardController::class, 'filterByWorkstation'])->name('dashboard.filter.workstation');
    Route::get('/dashboard/filter/supervisor/{supervisor}', [DashboardController::class, 'filterBySupervisor'])->name('dashboard.filter.supervisor');
    Route::get('/dashboard/filter/qualification/{qualification}', [DashboardController::class, 'filterByQualification'])->name('dashboard.filter.qualification');
    Route::get('/dashboard/filter/competency/{competency}', [DashboardController::class, 'filterByCompetency'])->name('dashboard.filter.competency');
    
    Route::post('/dashboard/change-password', [DashboardController::class, 'changePassword'])->name('dashboard.change-password');
    Route::post('/dashboard/add-user', [DashboardController::class, 'addUser'])->name('dashboard.add-user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/training-needs-assessment', [TrainingNeedsAssessmentController::class, 'create'])
    ->name('training.form');
Route::post('/training-needs-assessment/part-a', [TrainingNeedsAssessmentController::class, 'storePartA'])
    ->name('training.form.part-a');
Route::get('/training-needs-assessment/supervisor/{token}', [TrainingNeedsAssessmentController::class, 'showSupervisorForm'])
    ->name('training.supervisor.form');
Route::post('/training-needs-assessment/supervisor/{token}/part-b', [TrainingNeedsAssessmentController::class, 'storePartB'])
    ->name('training.supervisor.part-b');
Route::get('/training-needs-assessment/retrieve-link', [TrainingNeedsAssessmentController::class, 'showRetrieveLink'])
    ->name('training.retrieve.link');
Route::post('/training-needs-assessment/retrieve-link', [TrainingNeedsAssessmentController::class, 'retrieveLink'])
    ->name('training.retrieve.link.submit');
