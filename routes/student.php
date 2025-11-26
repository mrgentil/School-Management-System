<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\LibraryController as StudentLibraryController;
use App\Http\Controllers\Student\FinanceController as StudentFinanceController;
use App\Http\Controllers\Student\MaterialController as StudentMaterialController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\Student\StudentAssignmentController;
use App\Http\Controllers\Student\MyGradesController;
use App\Http\Controllers\Student\MessageController as StudentMessageController;
use App\Http\Controllers\Student\BookRequestController as StudentBookRequestController;
use App\Http\Controllers\Student\TimetableController as StudentTimetableController;

Route::group(['middleware' => ['auth', 'student'], 'prefix' => 'student', 'as' => 'student.'], function() {
    
    // Dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Route de test pour la messagerie
    Route::post('/test-message', function(\Illuminate\Http\Request $request) {
        dd([
            'POST_DATA' => $request->all(),
            'recipients' => $request->recipients,
            'subject' => $request->subject,
            'content' => $request->content,
            'has_files' => $request->hasFile('attachments'),
            'auth_user' => auth()->user()->name,
            'auth_id' => auth()->id(),
        ]);
    })->name('test.message');
    
    // Bibliothèque
    Route::group(['prefix' => 'library', 'as' => 'library.'], function() {
        Route::get('/', [StudentLibraryController::class, 'index'])->name('index');
        Route::get('/search', [StudentLibraryController::class, 'search'])->name('search');

        // Mes demandes - doit être avant /{book} pour éviter les conflits
        Route::get('/my-requests', [StudentBookRequestController::class, 'index'])->name('requests.index');
        Route::get('/my-requests/create', [StudentBookRequestController::class, 'create'])->name('requests.create');
        Route::post('/my-requests', [StudentBookRequestController::class, 'store'])->name('requests.store');
        Route::get('/my-requests/{bookRequest}', [StudentBookRequestController::class, 'show'])->name('requests.show');
        Route::post('/my-requests/{bookRequest}/cancel', [StudentBookRequestController::class, 'cancel'])->name('requests.cancel');

        // Routes pour les demandes de livres
        Route::prefix('books')->name('books.')->group(function() {
            Route::post('/{book}/request', [StudentLibraryController::class, 'requestBook'])->name('request');
        });

        // Ancienne route maintenue pour la rétrocompatibilité
        Route::post('/request/{book}', [StudentLibraryController::class, 'requestBook'])->name('request.legacy');

        // Cette route doit être en dernier pour éviter les conflits avec les autres routes
        Route::get('/{book}', [StudentLibraryController::class, 'show'])->name('show');
    });

    // Finance
    Route::group(['prefix' => 'finance', 'as' => 'finance.'], function() {
        Route::get('/', [StudentFinanceController::class, 'dashboard'])->name('dashboard');
        
        // Payments
        Route::get('/payments', [StudentFinanceController::class, 'payments'])->name('payments');
        Route::get('/payments/print', [StudentFinanceController::class, 'printPayments'])->name('payments.print');
        
        // TEST ROUTE
        Route::get('/test', function() {
            return 'TEST ROUTE WORKS!';
        });
        
        // Receipts - routes spécifiques en premier
        Route::get('/receipts/print', [StudentFinanceController::class, 'printReceipts'])->name('receipts.print');
        Route::get('/receipts/download-all', [StudentFinanceController::class, 'downloadAllReceipts'])->name('receipts.download_all');
        Route::get('/receipts', [StudentFinanceController::class, 'receipts'])->name('receipts');
        
        // Single receipt - routes avec paramètres en dernier
        Route::get('/receipt/{id}/download', [StudentFinanceController::class, 'downloadReceipt'])->name('receipt.download')->where('id', '[0-9]+');
        Route::get('/receipt/{id}/print', [StudentFinanceController::class, 'printReceipt'])->name('receipt.print')->where('id', '[0-9]+');
        Route::get('/receipt/{id}', [StudentFinanceController::class, 'showReceipt'])->name('receipt')->where('id', '[0-9]+');
    });

    // Matériel pédagogique
    Route::group(['prefix' => 'materials', 'as' => 'materials.'], function() {
        Route::get('/', [StudentMaterialController::class, 'index'])->name('index');
        Route::get('/{studyMaterial}/download', [StudentMaterialController::class, 'download'])->name('download');
        Route::get('/{studyMaterial}', [StudentMaterialController::class, 'show'])->name('show');
    });

    // Présences
    Route::group(['prefix' => 'attendance', 'as' => 'attendance.'], function() {
        Route::get('/', [StudentAttendanceController::class, 'index'])->name('index');
        Route::get('/calendar', [StudentAttendanceController::class, 'calendar'])->name('calendar');
    });

    // Devoirs
    Route::group(['prefix' => 'assignments', 'as' => 'assignments.'], function() {
        Route::get('/', [StudentAssignmentController::class, 'index'])->name('index');
        Route::get('/{assignment}', [StudentAssignmentController::class, 'show'])->name('show');
        Route::post('/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('submit');
        Route::get('/{assignment}/download', [StudentAssignmentController::class, 'downloadFile'])->name('download');
    });

    // Mes Notes
    Route::group(['prefix' => 'grades', 'as' => 'grades.'], function() {
        Route::get('/', [MyGradesController::class, 'index'])->name('index');
        Route::get('/bulletin', [MyGradesController::class, 'bulletin'])->name('bulletin');
    });

    // Examens - Hub principal
    Route::get('/exams', [\App\Http\Controllers\Student\ExamController::class, 'index'])->name('exams.index');
    
    // Calendrier d'Examens
    Route::get('/exam-schedule', [\App\Http\Controllers\StudentController::class, 'examSchedule'])->name('exam_schedule');
    
    // Ma Progression
    Route::get('/my-progress', [\App\Http\Controllers\Student\ProgressController::class, 'index'])->name('progress.index');

    // Emploi du temps
    Route::group(['prefix' => 'timetable', 'as' => 'timetable.'], function() {
        Route::get('/', [StudentTimetableController::class, 'index'])->name('index');
        Route::get('/calendar', [StudentTimetableController::class, 'calendar'])->name('calendar');
    });

    // Messagerie
    Route::group(['prefix' => 'messages', 'as' => 'messages.'], function() {
        Route::get('/', [StudentMessageController::class, 'index'])->name('index');
        Route::get('/create', [StudentMessageController::class, 'create'])->name('create');
        Route::post('/', [StudentMessageController::class, 'store'])->name('store');
        Route::get('/{id}', [StudentMessageController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::post('/{id}/reply', [StudentMessageController::class, 'reply'])->name('reply')->where('id', '[0-9]+');
    });

    // Demandes de livres
    Route::group(['prefix' => 'book-requests', 'as' => 'book-requests.'], function() {
        Route::get('/', [StudentBookRequestController::class, 'index'])->name('index');
        Route::get('/create', [StudentBookRequestController::class, 'create'])->name('create');
        Route::post('/', [StudentBookRequestController::class, 'store'])->name('store');
        Route::get('/{id}', [StudentBookRequestController::class, 'show'])->name('show');
    });

    // Notifications
    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function() {
        Route::get('/', [\App\Http\Controllers\Student\NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [\App\Http\Controllers\Student\NotificationController::class, 'getUnread'])->name('unread');
        Route::post('/{id}/read', [\App\Http\Controllers\Student\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [\App\Http\Controllers\Student\NotificationController::class, 'markAllAsRead'])->name('mark_all_read');
        Route::delete('/{id}', [\App\Http\Controllers\Student\NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/clear-read', [\App\Http\Controllers\Student\NotificationController::class, 'clearRead'])->name('clear_read');
    });
});
