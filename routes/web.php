<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrainingNeedsAssessmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/questions', [AuthController::class, 'questions'])->name('dashboard.questions');
    Route::get('/dashboard/responses', [AuthController::class, 'responses'])->name('dashboard.responses');
    Route::get('/dashboard/report', [AuthController::class, 'report'])->name('dashboard.report');
    Route::get('/dashboard/report/export', [AuthController::class, 'exportReport'])->name('dashboard.report.export');
    Route::get('/dashboard/settings', [AuthController::class, 'settings'])->name('dashboard.settings');
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard.questions');
    })->name('dashboard');
    Route::get('/dashboard/response/{id}', [AuthController::class, 'showResponse'])->name('dashboard.response');
    Route::post('/dashboard/response/{id}/mark-read', [AuthController::class, 'markAsRead'])->name('dashboard.response.mark-read');
    Route::delete('/dashboard/response/{id}', [AuthController::class, 'deleteResponse'])->name('dashboard.response.delete');
    
    // Filter routes
    Route::get('/dashboard/filter/gender/{gender}', [AuthController::class, 'filterByGender'])->name('dashboard.filter.gender');
    Route::get('/dashboard/filter/age/{range}', [AuthController::class, 'filterByAge'])->name('dashboard.filter.age');
    Route::get('/dashboard/filter/workstation/{workstation}', [AuthController::class, 'filterByWorkstation'])->name('dashboard.filter.workstation');
    Route::get('/dashboard/filter/supervisor/{supervisor}', [AuthController::class, 'filterBySupervisor'])->name('dashboard.filter.supervisor');
    Route::get('/dashboard/filter/qualification/{qualification}', [AuthController::class, 'filterByQualification'])->name('dashboard.filter.qualification');
    Route::get('/dashboard/filter/competency/{competency}', [AuthController::class, 'filterByCompetency'])->name('dashboard.filter.competency');
    
    Route::post('/dashboard/change-password', [AuthController::class, 'changePassword'])->name('dashboard.change-password');
    Route::post('/dashboard/add-user', [AuthController::class, 'addUser'])->name('dashboard.add-user');
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
