<?php

// Routes étudiant
require __DIR__.'/student.php';

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

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
    Route::group([], function(){

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

            /* Assignation de classes */
            Route::get('assign-class', [\App\Http\Controllers\SupportTeam\StudentRecordController::class, 'assign_class'])->name('students.assign_class');
            Route::post('assign-class', [\App\Http\Controllers\SupportTeam\StudentRecordController::class, 'store_assignment'])->name('students.store_assignment');
            Route::put('update-assignment/{sr_id}', [\App\Http\Controllers\SupportTeam\StudentRecordController::class, 'update_assignment'])->name('students.update_assignment');

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

            /*************** Import/Export Excel *****************/
            Route::group(['middleware' => 'teamSA'], function(){
                Route::get('download-template/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'download_template'])->name('tt.download_template');
                Route::post('import/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'import_timetable'])->name('tt.import');
                Route::get('export/{ttr}', [\App\Http\Controllers\SupportTeam\TimeTableController::class, 'export_timetable'])->name('tt.export');
            });

        });

        /*************** Attendance *****************/
        Route::group(['prefix' => 'attendance', 'middleware' => 'teamSAT'], function(){
            Route::get('/', [\App\Http\Controllers\SupportTeam\AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('/get-students', [\App\Http\Controllers\SupportTeam\AttendanceController::class, 'getStudents'])->name('attendance.get_students');
            Route::post('/store', [\App\Http\Controllers\SupportTeam\AttendanceController::class, 'store'])->name('attendance.store');
            Route::get('/view', [\App\Http\Controllers\SupportTeam\AttendanceController::class, 'view'])->name('attendance.view');
            Route::get('/statistics', [\App\Http\Controllers\SupportTeam\AttendanceController::class, 'statistics'])->name('attendance.statistics');
            Route::get('/export', [\App\Http\Controllers\SupportTeam\AttendanceController::class, 'export'])->name('attendance.export');
            Route::delete('/{id}', [\App\Http\Controllers\SupportTeam\AttendanceController::class, 'destroy'])->name('attendance.destroy')->middleware('teamSA');
            Route::get('/get-sections/{class_id}', [\App\Http\Controllers\SupportTeam\AttendanceController::class, 'getSections'])->name('attendance.get_sections');
            Route::get('/get-subjects/{class_id}', [\App\Http\Controllers\SupportTeam\AttendanceController::class, 'getSubjects'])->name('attendance.get_subjects');
        });

        /*************** Assignments (Devoirs) *****************/
        Route::group(['prefix' => 'assignments', 'middleware' => 'teamSAT'], function(){
            Route::get('/', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'index'])->name('assignments.index');
            Route::get('/create', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'create'])->name('assignments.create');
            Route::get('/get-sections/{class_id}', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'getSections']);
            Route::get('/{assignment}/edit', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'edit'])->name('assignments.edit');
            Route::get('/{assignment}/export', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'export'])->name('assignments.export');
            Route::get('/{assignment}', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'show'])->name('assignments.show');
            Route::post('/', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'store'])->name('assignments.store');
            Route::put('/{assignment}', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'update'])->name('assignments.update');
            Route::delete('/{assignment}', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'destroy'])->name('assignments.destroy');
            Route::post('/{assignment}/grade', [\App\Http\Controllers\SupportTeam\AssignmentController::class, 'grade'])->name('assignments.grade');
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

        /*************** Analytics Avancés *****************/
        Route::group(['prefix' => 'exam-analytics', 'middleware' => 'teamSA'], function(){
            Route::get('/', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'index'])->name('exam_analytics.index');
            Route::get('/dashboard', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'dashboard'])->name('exam_analytics.dashboard');
            Route::get('/overview/{exam_id}', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'overview'])->name('exam_analytics.overview');
            Route::get('/class-analysis/{exam_id}/{class_id}', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'classAnalysis'])->name('exam_analytics.class_analysis');
            Route::get('/student-progress/{student_id}', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'studentProgress'])->name('exam_analytics.student_progress');
            Route::get('/student-progress-chart/{student_id}', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'studentProgressChart'])->name('exam_analytics.student_progress_chart');
            Route::get('/historical-comparison', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'historicalComparison'])->name('exam_analytics.historical_comparison');
            Route::get('/struggling-students', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'strugglingStudentsAlert'])->name('exam_analytics.struggling_students');
            Route::get('/subject-teacher-reports', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'subjectTeacherReports'])->name('exam_analytics.subject_teacher_reports');
            Route::get('/rankings', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'rankings'])->name('exam_analytics.rankings');
            
            // Import/Export
            Route::post('/import-marks', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'importMarks'])->name('exam_analytics.import_marks');
            Route::post('/export-results', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'exportResults'])->name('exam_analytics.export_results');
            
            // Notifications
            Route::post('/send-parent-notifications', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'sendParentNotifications'])->name('exam_analytics.send_parent_notifications');
        });

        Route::get('students/statistics', [\App\Http\Controllers\SupportTeam\StudentStatsController::class, 'index'])->name('students.statistics');
        Route::resource('students', '\App\Http\Controllers\SupportTeam\StudentRecordController');
        Route::resource('users', '\App\Http\Controllers\SupportTeam\UserController');
        Route::resource('classes', '\App\Http\Controllers\SupportTeam\MyClassController');
        Route::resource('sections', '\App\Http\Controllers\SupportTeam\SectionController');
        Route::resource('subjects', '\App\Http\Controllers\SupportTeam\SubjectController');
        Route::resource('grades', '\App\Http\Controllers\SupportTeam\GradeController');
        
        // Custom Remarks (Mentions personnalisées)
        Route::post('custom-remarks', [CustomRemarkController::class, 'store'])->name('custom-remarks.store');
        Route::put('custom-remarks/{id}', [CustomRemarkController::class, 'update'])->name('custom-remarks.update');
        Route::delete('custom-remarks/{id}', [CustomRemarkController::class, 'destroy'])->name('custom-remarks.destroy');

        // Routes pour la configuration des cotes par matière (Système RDC)
        Route::get('subject-grades-config', [\App\Http\Controllers\SupportTeam\SubjectGradeConfigController::class, 'index'])->name('subject-grades-config.index');
        Route::get('subject-grades-config/{classId}', [\App\Http\Controllers\SupportTeam\SubjectGradeConfigController::class, 'show'])->name('subject-grades-config.show');
        Route::post('subject-grades-config', [\App\Http\Controllers\SupportTeam\SubjectGradeConfigController::class, 'store'])->name('subject-grades-config.store');
        Route::put('subject-grades-config/{id}', [\App\Http\Controllers\SupportTeam\SubjectGradeConfigController::class, 'update'])->name('subject-grades-config.update');
        Route::delete('subject-grades-config/{id}', [\App\Http\Controllers\SupportTeam\SubjectGradeConfigController::class, 'destroy'])->name('subject-grades-config.destroy');
        Route::post('subject-grades-config/duplicate', [\App\Http\Controllers\SupportTeam\SubjectGradeConfigController::class, 'duplicate'])->name('subject-grades-config.duplicate');
        Route::get('subject-grades-config/{classId}/initialize-defaults', [\App\Http\Controllers\SupportTeam\SubjectGradeConfigController::class, 'initializeDefaults'])->name('subject-grades-config.initialize-defaults');

        // Exams
        Route::get('exams/dashboard', [\App\Http\Controllers\SupportTeam\ExamController::class, 'dashboard'])->name('exams.dashboard');
        Route::resource('exams', '\App\Http\Controllers\SupportTeam\ExamController');
        
        /*************** Exam Schedules & Calendar *****************/
        Route::group(['prefix' => 'exam-schedules', 'as' => 'exam_schedules.'], function () {
            Route::get('/', [\App\Http\Controllers\SupportTeam\ExamScheduleController::class, 'index'])->name('index');
            Route::get('calendar', [\App\Http\Controllers\SupportTeam\ExamScheduleController::class, 'calendar'])->name('calendar');
            Route::get('{exam}', [\App\Http\Controllers\SupportTeam\ExamScheduleController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\SupportTeam\ExamScheduleController::class, 'store'])->name('store');
            Route::post('{exam}/bulk-store', [\App\Http\Controllers\SupportTeam\ExamScheduleController::class, 'bulkStore'])->name('bulk_store');
            Route::put('{id}', [\App\Http\Controllers\SupportTeam\ExamScheduleController::class, 'update'])->name('update');
            Route::delete('{id}', [\App\Http\Controllers\SupportTeam\ExamScheduleController::class, 'destroy'])->name('destroy');
            Route::post('add-supervisor', [\App\Http\Controllers\SupportTeam\ExamScheduleController::class, 'addSupervisor'])->name('add_supervisor');
            Route::delete('supervisor/{id}', [\App\Http\Controllers\SupportTeam\ExamScheduleController::class, 'removeSupervisor'])->name('remove_supervisor');
        });

        /*************** Exam Analytics & Reports *****************/
        Route::group(['prefix' => 'exam-analytics', 'as' => 'exam_analytics.'], function () {
            Route::get('/', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'index'])->name('index');
            Route::get('exam/{exam}/overview', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'overview'])->name('overview');
            Route::get('exam/{exam}/class/{class}', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'classAnalysis'])->name('class_analysis');
            Route::get('student/{student}/progress', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'studentProgress'])->name('student_progress');
            Route::post('export', [\App\Http\Controllers\SupportTeam\ExamAnalyticsController::class, 'export'])->name('export');
        });

        /*************** Exam Publication *****************/
        Route::group(['prefix' => 'exam-publication', 'as' => 'exam_publication.'], function () {
            Route::get('{exam}', [\App\Http\Controllers\SupportTeam\ExamPublicationController::class, 'show'])->name('show');
            Route::post('{exam}/publish', [\App\Http\Controllers\SupportTeam\ExamPublicationController::class, 'publish'])->name('publish');
            Route::post('{exam}/unpublish', [\App\Http\Controllers\SupportTeam\ExamPublicationController::class, 'unpublish'])->name('unpublish');
            Route::post('{exam}/notify', [\App\Http\Controllers\SupportTeam\ExamPublicationController::class, 'sendNotification'])->name('notify');
        });

        /*************** Exam Rooms (Salles d'Examen) *****************/
        Route::resource('exam-rooms', '\App\Http\Controllers\SupportTeam\ExamRoomController');

        /*************** Exam Placements (Placements Étudiants - SESSION) *****************/
        // LOGIQUE CORRECTE: Placements au niveau de l'EXAMEN, pas de l'horaire individuel
        Route::group(['prefix' => 'exam-placements', 'as' => 'exam_placements.'], function () {
            Route::post('{exam_id}/generate', [\App\Http\Controllers\SupportTeam\ExamPlacementController::class, 'generate'])->name('generate');
            Route::get('{exam_id}', [\App\Http\Controllers\SupportTeam\ExamPlacementController::class, 'show'])->name('show');
            Route::get('{exam_id}/room/{room_id}', [\App\Http\Controllers\SupportTeam\ExamPlacementController::class, 'byRoom'])->name('by_room');
            Route::delete('{exam_id}', [\App\Http\Controllers\SupportTeam\ExamPlacementController::class, 'destroy'])->name('destroy');
        });
        
        Route::resource('dorms', '\App\Http\Controllers\SupportTeam\DormController');
        Route::resource('payments', '\App\Http\Controllers\SupportTeam\PaymentController');

        /*************** Sections académiques & Options *****************/
        Route::group(['middleware' => 'teamSA'], function () {
            Route::resource('academic_sections', \App\Http\Controllers\SupportTeam\AcademicSectionController::class)
                ->except(['create', 'show', 'edit']);

            Route::resource('options', \App\Http\Controllers\SupportTeam\OptionController::class)
                ->except(['create', 'show', 'edit']);
        });

    });

    /*************** Notices & Events *****************/
    Route::resource('notices', '\App\Http\Controllers\SupportTeam\NoticeController');
    Route::resource('events', '\App\Http\Controllers\SupportTeam\SchoolEventController');
    Route::get('events/calendar/data', [\App\Http\Controllers\SupportTeam\SchoolEventController::class, 'calendar'])->name('events.calendar');

    /*************** Books *****************/
    Route::resource('books', '\App\Http\Controllers\BookController');

    /*************** Study Materials *****************/
    Route::resource('study-materials', '\App\Http\Controllers\StudyMaterialController');
    Route::get('study-materials/{studyMaterial}/download', [\App\Http\Controllers\StudyMaterialController::class, 'download'])->name('study-materials.download');

    /*************** Book Requests *****************/
    Route::resource('book-requests', '\App\Http\Controllers\SupportTeam\BookRequestController');
    Route::put('book-requests/{bookRequest}/approve', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'approve'])->name('book-requests.approve');
    Route::put('book-requests/{bookRequest}/reject', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'reject'])->name('book-requests.reject');
    Route::put('book-requests/{bookRequest}/return', [\App\Http\Controllers\SupportTeam\BookRequestController::class, 'returnBook'])->name('book-requests.return');

    /************************ AJAX ****************************/
    Route::group(['prefix' => 'ajax'], function() {
        Route::get('get_lga/{state_id}', [\App\Http\Controllers\AjaxController::class, 'get_lga'])->name('get_lga');
        Route::get('get_class_sections/{class_id}', [\App\Http\Controllers\AjaxController::class, 'get_class_sections'])->name('get_class_sections');
        Route::get('get_class_subjects/{class_id}', [\App\Http\Controllers\AjaxController::class, 'get_class_subjects'])->name('get_class_subjects');
    });

});

/************************ SUPER ADMIN ****************************/
// Settings (accessible depuis n'importe où pour le super admin)
Route::group(['middleware' => 'super_admin'], function(){
    Route::get('/settings', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'index'])->name('settings');
    Route::put('/settings', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'update'])->name('settings.update');
});

Route::group(['middleware' => 'super_admin', 'prefix' => 'super_admin', 'as' => 'super_admin.'], function(){

    // Dashboard Super Admin
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

    // Messagerie Admin
    Route::group(['prefix' => 'messages', 'as' => 'messages.'], function() {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\MessageController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\MessageController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\MessageController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\MessageController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\MessageController::class, 'destroy'])->name('destroy');
    });

});



/************************ PARENT ****************************/
Route::group(['middleware' => 'my_parent'], function(){

    Route::get('/my_children', [\App\Http\Controllers\MyParent\MyController::class, 'children'])->name('my_children');

});

    /*************** Accountant *****************/
Route::group(['middleware' => 'accountant', 'prefix' => 'accountant', 'as' => 'accountant.'], function(){

    // Dashboard
    Route::get('/dashboard', function() {
        return view('pages.accountant.dashboard');
    })->name('dashboard');

});

    /*************** Librarian *****************/
Route::group(['middleware' => 'librarian', 'prefix' => 'librarian', 'as' => 'librarian.'], function(){

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Librarian\DashboardController::class, 'index'])->name('dashboard');

    // Gestion des livres
    Route::resource('books', \App\Http\Controllers\Librarian\BookController::class);

    // Gestion des demandes de livres
    Route::get('/book-requests', [\App\Http\Controllers\Librarian\BookRequestController::class, 'index'])->name('book-requests.index');
    Route::get('/book-requests/overdue/list', [\App\Http\Controllers\Librarian\BookRequestController::class, 'overdue'])->name('book-requests.overdue');
    Route::get('/book-requests/{bookRequest}', [\App\Http\Controllers\Librarian\BookRequestController::class, 'show'])->name('book-requests.show');
    Route::post('/book-requests/{bookRequest}/approve', [\App\Http\Controllers\Librarian\BookRequestController::class, 'approve'])->name('book-requests.approve');
    Route::post('/book-requests/{bookRequest}/reject', [\App\Http\Controllers\Librarian\BookRequestController::class, 'reject'])->name('book-requests.reject');
    Route::post('/book-requests/{bookRequest}/mark-borrowed', [\App\Http\Controllers\Librarian\BookRequestController::class, 'markAsBorrowed'])->name('book-requests.mark-borrowed');
    Route::post('/book-requests/{bookRequest}/mark-returned', [\App\Http\Controllers\Librarian\BookRequestController::class, 'markAsReturned'])->name('book-requests.mark-returned');
    Route::post('/book-requests/{bookRequest}/send-reminder', [\App\Http\Controllers\Librarian\BookRequestController::class, 'sendReminder'])->name('book-requests.send-reminder');

    // Rapports
    Route::get('/reports', [\App\Http\Controllers\Librarian\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/popular-books', [\App\Http\Controllers\Librarian\ReportController::class, 'popularBooks'])->name('reports.popular-books');
    Route::get('/reports/active-students', [\App\Http\Controllers\Librarian\ReportController::class, 'activeStudents'])->name('reports.active-students');
    Route::get('/reports/monthly', [\App\Http\Controllers\Librarian\ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/inventory', [\App\Http\Controllers\Librarian\ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/penalties', [\App\Http\Controllers\Librarian\ReportController::class, 'penalties'])->name('reports.penalties');
    Route::post('/reports/export', [\App\Http\Controllers\Librarian\ReportController::class, 'export'])->name('reports.export');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
