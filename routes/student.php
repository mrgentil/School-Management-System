<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\LibraryController as StudentLibraryController;
use App\Http\Controllers\Student\FinanceController as StudentFinanceController;
use App\Http\Controllers\Student\MaterialController as StudentMaterialController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\Student\MessageController as StudentMessageController;
use App\Http\Controllers\Student\BookRequestController as StudentBookRequestController;

Route::group(['middleware' => ['auth', 'student'], 'prefix' => 'student', 'as' => 'student.'], function() {
    
    // Bibliothèque
    Route::group(['prefix' => 'library', 'as' => 'library.'], function() {
        Route::get('/', [StudentLibraryController::class, 'index'])->name('index');
        Route::get('/search', [StudentLibraryController::class, 'search'])->name('search');
        Route::get('/{book}', [StudentLibraryController::class, 'show'])->name('show');
        
        // Routes pour les demandes de livres
        Route::prefix('books')->name('books.')->group(function() {
            Route::post('/{book}/request', [StudentLibraryController::class, 'requestBook'])->name('request');
        });
        
        // Ancienne route maintenue pour la rétrocompatibilité
        Route::post('/request/{book}', [StudentLibraryController::class, 'requestBook'])->name('request.legacy');
        
        // Mes demandes
        Route::get('/my-requests', [StudentBookRequestController::class, 'index'])->name('requests.index');
        Route::get('/my-requests/create', [StudentBookRequestController::class, 'create'])->name('requests.create');
        Route::post('/my-requests', [StudentBookRequestController::class, 'store'])->name('requests.store');
        Route::get('/my-requests/{bookRequest}', [StudentBookRequestController::class, 'show'])->name('requests.show');
    });

    // Finance
    Route::group(['prefix' => 'finance', 'as' => 'finance.'], function() {
        Route::get('/', [StudentFinanceController::class, 'dashboard'])->name('dashboard');
        Route::get('/payments', [StudentFinanceController::class, 'payments'])->name('payments');
        Route::get('/receipts', [StudentFinanceController::class, 'receipts'])->name('receipts');
        Route::get('/receipt/{id}', [StudentFinanceController::class, 'showReceipt'])->name('receipt');
    });

    // Matériel pédagogique
    Route::group(['prefix' => 'materials', 'as' => 'materials.'], function() {
        Route::get('/', [StudentMaterialController::class, 'index'])->name('index');
        Route::get('/{id}', [StudentMaterialController::class, 'show'])->name('show');
    });

    // Présences
    Route::group(['prefix' => 'attendance', 'as' => 'attendance.'], function() {
        Route::get('/', [StudentAttendanceController::class, 'index'])->name('index');
        Route::get('/calendar', [StudentAttendanceController::class, 'calendar'])->name('calendar');
    });

    // Devoirs
    Route::group(['prefix' => 'assignments', 'as' => 'assignments.'], function() {
        Route::get('/', [StudentAssignmentController::class, 'index'])->name('index');
        Route::get('/{id}', [StudentAssignmentController::class, 'show'])->name('show');
    });

    // Messagerie
    Route::group(['prefix' => 'messages', 'as' => 'messages.'], function() {
        Route::get('/', [StudentMessageController::class, 'index'])->name('index');
        Route::get('/create', [StudentMessageController::class, 'create'])->name('create');
        Route::post('/', [StudentMessageController::class, 'store'])->name('store');
        Route::get('/{id}', [StudentMessageController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [StudentMessageController::class, 'reply'])->name('reply');
    });

    // Demandes de livres
    Route::group(['prefix' => 'book-requests', 'as' => 'book-requests.'], function() {
        Route::get('/', [StudentBookRequestController::class, 'index'])->name('index');
        Route::get('/create', [StudentBookRequestController::class, 'create'])->name('create');
        Route::post('/', [StudentBookRequestController::class, 'store'])->name('store');
        Route::get('/{id}', [StudentBookRequestController::class, 'show'])->name('show');
    });
});
