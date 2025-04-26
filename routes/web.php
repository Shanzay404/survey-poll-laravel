<?php

use App\Http\Controllers\PollController;
use App\Http\Controllers\SurveyController;
use App\Models\Survey;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('form');
Route::post('store', [SurveyController::class, 'store'])->name('form.submit');

Route::get('view-poll/{poll}', [PollController::class, 'show'])->name('poll.show');    // drag & drop (id change)
Route::get('edit-poll/{poll}', [PollController::class, 'edit'])->name('poll.edit');
Route::put('update-poll/{poll}', [PollController::class, 'update'])->name('poll.update'); 
Route::delete('/poll/delete-option/{id}', [PollController::class, 'deleteOption']);








// pending work
Route::get('view-survey/{survey}', [SurveyController::class, 'show'])->name('survey.show');
Route::get('edit-survey/{survey}', [SurveyController::class, 'edit'])->name('survey.edit');
Route::delete('/survey/delete-question/{id}', [SurveyController::class, 'deleteQuestion']);
Route::put('update-survey/{survey}', [SurveyController::class, 'update'])->name('survey.update');

Route::post('/update-option-order', [PollController::class, 'updateOrder'])->name('update.option.order');