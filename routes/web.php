<?php

Auth::routes();

// Routes étudiant
require __DIR__.'/student.php';

use App\Http\Controllers\HomeController;

//Route::get('/test', 'TestController@index')->name('test');
Route::get('/privacy-policy', [HomeController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/terms-of-use', [HomeController::class, 'terms_of_use'])->name('terms_of_use');


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'dashboard'])->name('home');
    Route::get('/home', [HomeController::class, 'dashboard'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::group(['prefix' => 'my_account'], function() {
        Route::get('/', [\App\Http\Controllers\MyAccountController::class, 'edit_profile'])->name('my_account');
        Route::put('/', [\App\Http\Controllers\MyAccountController::class, 'update_profile'])->name('my_account.update');
        Route::put('/change_password', [\App\Http\Controllers\MyAccountController::class, 'change_pass'])->name('my_account.change_pass');
    });

    /*************** Support Team *****************/
    Route::group(['namespace' => 'SupportTeam',], function(){
        
        // Gestion des demandes de livres
        Route::group(['prefix' => 'book-requests', 'as' => 'book-requests.'], function() {
            Route::get('/', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'index'])->name('index');
            Route::get('/{bookRequest}', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'show'])->name('show');
            Route::post('/{bookRequest}/approve', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'approve'])->name('approve');
            Route::post('/{bookRequest}/reject', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'reject'])->name('reject');
            Route::delete('/{bookRequest}', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'destroy'])->name('destroy');
        });
        

        /*************** Students *****************/
        Route::group(['prefix' => 'students'], function(){
            Route::get('reset_pass/{st_id}', [\App\Http\Controllers\SupportTeam\StudentRecordController::class, 'reset_pass'])->name('st.reset_pass');
            Route::get('graduated', [\App\Http\Controllers\SupportTeam\StudentRecordController::class, 'graduated'])->name('students.graduated');
            Route::put('not_graduated/{id}', [\App\Http\Controllers\SupportTeam\StudentRecordController::class, 'not_graduated'])->name('st.not_graduated');
            Route::get('list/{class_id}', [\App\Http\Controllers\SupportTeam\StudentRecordController::class, 'listByClass'])->name('students.list')->middleware('teamSAT');

            /* Promotions */
            Route::post('promote_selector', [\App\Http\Controllers\SupportTeam\PromotionController::class, 'selector'])->name('students.promote_selector');
            Route::get('promotion/manage', [\App\Http\Controllers\SupportTeam\PromotionController::class, 'manage'])->name('students.promotion_manage');
            Route::delete('promotion/reset/{pid}', [\App\Http\Controllers\SupportTeam\PromotionController::class, 'reset'])->name('students.promotion_reset');
            Route::delete('promotion/reset_all', [\App\Http\Controllers\SupportTeam\PromotionController::class, 'reset_all'])->name('students.promotion_reset_all');
            Route::get('promotion/{fc?}/{fs?}/{tc?}/{ts?}', [\App\Http\Controllers\SupportTeam\PromotionController::class, 'promotion'])->name('students.promotion');
            Route::post('promote/{fc}/{fs}/{tc}/{ts}', [\App\Http\Controllers\SupportTeam\PromotionController::class, 'promote'])->name('students.promote');

        });

        /*************** Users *****************/
        Route::group(['prefix' => 'users'], function(){
            Route::get('reset_pass/{id}', [\App\Http\Controllers\SupportTeam\UserController::class, 'reset_pass'])->name('users.reset_pass');
        });

        /*************** Book Requests *****************/
        Route::group(['prefix' => 'book-requests', 'as' => 'book-requests.'], function() {
            Route::get('/', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'reject'])->name('reject');
            Route::delete('/{id}', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'destroy'])->name('destroy');
        });

        /*************** TimeTables *****************/
        Route::group(['prefix' => 'timetables'], function(){
            Route::get('/', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'index'])->name('tt.index');

            Route::group(['middleware' => 'teamSA'], function() {
                Route::post('/', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'store'])->name('tt.store');
                Route::put('/{tt}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'update'])->name('tt.update');
                Route::delete('/{tt}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'delete'])->name('tt.delete');
            });

            /*************** TimeTable Records *****************/
            Route::group(['prefix' => 'records'], function(){

                Route::group(['middleware' => 'teamSA'], function(){
                    Route::get('manage/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'manage'])->name('ttr.manage');
                    Route::post('/', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'store_record'])->name('ttr.store');
                    Route::get('edit/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'edit_record'])->name('ttr.edit');
                    Route::put('/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'update_record'])->name('ttr.update');
                });

                Route::get('show/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'show_record'])->name('ttr.show');
                Route::get('print/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'print_record'])->name('ttr.print');
                Route::delete('/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'delete_record'])->name('ttr.destroy');

            });

            /*************** Time Slots *****************/
            Route::group(['prefix' => 'time_slots', 'middleware' => 'teamSA'], function(){
                Route::post('/', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'store_time_slot'])->name('ts.store');
                Route::post('/use/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'use_time_slot'])->name('ts.use');
                Route::get('edit/{ts}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'edit_time_slot'])->name('ts.edit');
                Route::delete('/{ts}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'delete_time_slot'])->name('ts.destroy');
                Route::put('/{ts}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'update_time_slot'])->name('ts.update');
            });

        });

        /*************** Payments *****************/
        Route::group(['prefix' => 'payments'], function(){

            Route::get('manage/{class_id?}', [\App\Http\Controllers\SupportTeam\PaymentController::class, 'manage'])->name('payments.manage');
            Route::get('invoice/{id}/{year?}', [\App\Http\Controllers\SupportTeam\PaymentController::class, 'invoice'])->name('payments.invoice');
            Route::get('receipts/{id}', [\App\Http\Controllers\SupportTeam\PaymentController::class, 'receipts'])->name('payments.receipts');
            Route::get('pdf_receipts/{id}', [\App\Http\Controllers\SupportTeam\PaymentController::class, 'pdf_receipts'])->name('payments.pdf_receipts');
            Route::post('select_year', [\App\Http\Controllers\SupportTeam\PaymentController::class, 'select_year'])->name('payments.select_year');
            Route::post('select_class', [\App\Http\Controllers\SupportTeam\PaymentController::class, 'select_class'])->name('payments.select_class');
            Route::delete('reset_record/{id}', [\App\Http\Controllers\SupportTeam\PaymentController::class, 'reset_record'])->name('payments.reset_record');
            Route::post('pay_now/{id}', [\App\Http\Controllers\SupportTeam\PaymentController::class, 'pay_now'])->name('payments.pay_now');
        });

        /*************** Pins *****************/
        Route::group(['prefix' => 'pins'], function(){
            Route::get('create', [\App\Http\Controllers\SupportTeam\PinController::class, 'create'])->name('pins.create');
            Route::get('/', [\App\Http\Controllers\SupportTeam\PinController::class, 'index'])->name('pins.index');
            Route::post('/', [\App\Http\Controllers\SupportTeam\PinController::class, 'store'])->name('pins.store');
            Route::get('enter/{id}', [\App\Http\Controllers\SupportTeam\PinController::class, 'enter_pin'])->name('pins.enter');
            Route::post('verify/{id}', [\App\Http\Controllers\SupportTeam\PinController::class, 'verify'])->name('pins.verify');
            Route::delete('/', [\App\Http\Controllers\SupportTeam\PinController::class, 'destroy'])->name('pins.destroy');
        });

        /*************** Marks *****************/
        Route::group(['prefix' => 'marks'], function(){

           // FOR teamSA
            Route::group(['middleware' => 'teamSA'], function(){
                Route::get('batch_fix', [\App\Http\Controllers\SupportTeam\MarkController::class, 'batch_fix'])->name('marks.batch_fix');
                Route::put('batch_update', [\App\Http\Controllers\SupportTeam\MarkController::class, 'batch_update'])->name('marks.batch_update');
                Route::get('tabulation/{exam?}/{class?}/{sec_id?}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'tabulation'])->name('marks.tabulation');
                Route::post('tabulation', [\App\Http\Controllers\SupportTeam\MarkController::class, 'tabulation_select'])->name('marks.tabulation_select');
                Route::get('tabulation/print/{exam}/{class}/{sec_id}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'print_tabulation'])->name('marks.print_tabulation');
            });

            // FOR teamSAT
            Route::group(['middleware' => 'teamSAT'], function(){
                Route::get('/', [\App\Http\Controllers\SupportTeam\MarkController::class, 'index'])->name('marks.index');
                Route::get('manage/{exam}/{class}/{section}/{subject}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'manage'])->name('marks.manage');
                Route::put('update/{exam}/{class}/{section}/{subject}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'update'])->name('marks.update');
                Route::put('comment_update/{exr_id}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'comment_update'])->name('marks.comment_update');
                Route::put('skills_update/{skill}/{exr_id}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'skills_update'])->name('marks.skills_update');
                Route::post('selector', [\App\Http\Controllers\SupportTeam\MarkController::class, 'selector'])->name('marks.selector');
                Route::get('bulk/{class?}/{section?}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'bulk'])->name('marks.bulk');
                Route::post('bulk', [\App\Http\Controllers\SupportTeam\MarkController::class, 'bulk_select'])->name('marks.bulk_select');
            });

            Route::get('select_year/{id}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'year_selector'])->name('marks.year_selector');
            Route::post('select_year/{id}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'year_selected'])->name('marks.year_select');
            Route::get('show/{id}/{year}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'show'])->name('marks.show');
            Route::get('print/{id}/{exam_id}/{year}', [\App\Http\Controllers\SupportTeam\MarkController::class, 'print_view'])->name('marks.print');

        });

        Route::resource('students', '\App\Http\Controllers\SupportTeam\StudentRecordController');
        Route::resource('users', '\App\Http\Controllers\SupportTeam\UserController');
        Route::resource('classes', '\App\Http\Controllers\SupportTeam\MyClassController');
        Route::resource('sections', '\App\Http\Controllers\SupportTeam\SectionController');
        Route::resource('subjects', '\App\Http\Controllers\SupportTeam\SubjectController');
        Route::resource('grades', '\App\Http\Controllers\SupportTeam\GradeController');
        Route::resource('exams', '\App\Http\Controllers\SupportTeam\ExamController');
        Route::resource('dorms', '\App\Http\Controllers\SupportTeam\DormController');
        Route::resource('payments', '\App\Http\Controllers\SupportTeam\PaymentController');

    });

    /*************** Notices & Events *****************/
    Route::resource('notices', '\App\Http\Controllers\SupportTeam\NoticeController');
    Route::resource('events', '\App\Http\Controllers\SupportTeam\SchoolEventController');
    Route::get('events/calendar/data', '\App\Http\Controllers\SupportTeam\SchoolEventController@calendar')->name('events.calendar');

    /*************** Books *****************/
    Route::resource('books', '\App\Http\Controllers\BookController');

    /*************** Study Materials *****************/
    Route::resource('study-materials', '\App\Http\Controllers\StudyMaterialController');
    Route::get('study-materials/{studyMaterial}/download', '\App\Http\Controllers\StudyMaterialController@download')->name('study-materials.download');

    /*************** Book Requests *****************/
    Route::resource('book-requests', '\App\Http\Controllers\SupportTeam\BookRequestController');
    Route::put('book-requests/{bookRequest}/approve', '\App\Http\Controllers\SupportTeam\BookRequestController@approve')->name('book-requests.approve');
    Route::put('book-requests/{bookRequest}/reject', '\App\Http\Controllers\SupportTeam\BookRequestController@reject')->name('book-requests.reject');
    Route::put('book-requests/{bookRequest}/return', '\App\Http\Controllers\SupportTeam\BookRequestController@returnBook')->name('book-requests.return');

    /************************ AJAX ****************************/
    Route::group(['prefix' => 'ajax'], function() {
        Route::get('get_lga/{state_id}', '\App\Http\Controllers\AjaxController@get_lga')->name('get_lga');
        Route::get('get_class_sections/{class_id}', '\App\Http\Controllers\AjaxController@get_class_sections')->name('get_class_sections');
        Route::get('get_class_subjects/{class_id}', '\App\Http\Controllers\AjaxController@get_class_subjects')->name('get_class_subjects');
    });

});

/************************ SUPER ADMIN ****************************/
Route::group(['namespace' => 'App\Http\Controllers\SuperAdmin','middleware' => 'super_admin', 'prefix' => 'super_admin'], function(){

    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::put('/settings', 'SettingController@update')->name('settings.update');

});

/************************ STUDENT ****************************/
Route::group(['namespace' => 'App\\Http\\Controllers\\Student', 'middleware' => 'student', 'prefix' => 'student'], function(){

    // Library Routes
    Route::group(['prefix' => 'library'], function(){
        Route::get('/', 'LibraryController@index')->name('student.library.index');
        Route::get('/book/{book}', 'LibraryController@show')->name('student.library.show');
        Route::post('/request/{book}', 'LibraryController@requestBook')->name('student.library.request');
        Route::get('/my-requests', 'LibraryController@myRequests')->name('student.library.requests');
    });

    // Study Materials Routes
    Route::group(['prefix' => 'materials'], function(){
        Route::get('/', 'MaterialController@index')->name('student.materials.index');
        Route::get('/show/{material}', 'MaterialController@show')->name('student.materials.show');
        Route::get('/download/{material}', 'MaterialController@download')->name('student.materials.download');
    });

    // Finance Routes
    Route::group(['prefix' => 'finance'], function() {
        // Dashboard
        Route::get('/', 'FinanceController@dashboard')->name('student.finance.dashboard');
        
        // Payments
        Route::get('/payments', 'FinanceController@payments')->name('student.finance.payments');
        Route::get('/payments/print', 'FinanceController@printPayments')->name('student.finance.payments.print');
        
        // Receipts
        Route::get('/receipts', 'FinanceController@receipts')->name('student.finance.receipts');
        Route::get('/receipts/print', 'FinanceController@printReceipts')->name('student.finance.receipts.print');
        Route::get('/receipts/{id}', 'FinanceController@showReceipt')->name('student.finance.receipt');
        Route::get('/receipts/{id}/download', 'FinanceController@downloadReceipt')->name('student.finance.receipt.download');
        Route::get('/receipts/{id}/details', 'FinanceController@receiptDetails')->name('student.finance.receipt.details');
        Route::get('/receipts/{id}/print', 'FinanceController@printSingleReceipt')->name('student.finance.receipt.print');
        
        // Make Payment
        Route::post('/payments/{id}/pay', 'FinanceController@makePayment')->name('student.finance.pay');
    });

    // Attendance Routes
    Route::group(['prefix' => 'attendance'], function(){
        Route::get('/', 'AttendanceController@index')->name('student.attendance.index');
        Route::get('/calendar', 'AttendanceController@calendar')->name('student.attendance.calendar');
    });

    // Assignment Routes
    Route::group(['prefix' => 'assignments'], function(){
        Route::get('/', 'AssignmentController@index')->name('student.assignments.index');
        Route::get('/show/{assignment}', 'AssignmentController@show')->name('student.assignments.show');
        Route::post('/submit/{assignment}', 'AssignmentController@submit')->name('student.assignments.submit');
        Route::get('/download/{assignment}', 'AssignmentController@downloadFile')->name('student.assignments.download');
    });

    // Messages Routes
    Route::group(['prefix' => 'messages'], function(){
        Route::get('/', 'MessageController@inbox')->name('student.messages.inbox');
        Route::get('/show/{message}', 'MessageController@show')->name('student.messages.show');
        Route::get('/compose', 'MessageController@compose')->name('student.messages.compose');
        Route::post('/send', 'MessageController@send')->name('student.messages.send');
        Route::get('/reply/{message}', 'MessageController@reply')->name('student.messages.reply');
        Route::get('/download/{message}', 'MessageController@downloadAttachment')->name('student.messages.download');
        Route::post('/mark-read/{message}', 'MessageController@markAsRead')->name('student.messages.mark_read');
        Route::get('/unread-count', 'MessageController@getUnreadCount')->name('student.messages.unread_count');
    });

});

/************************ PARENT ****************************/
Route::group(['namespace' => 'App\Http\Controllers\MyParent','middleware' => 'my_parent',], function(){

    Route::get('/my_children', 'MyController@children')->name('my_children');

});
