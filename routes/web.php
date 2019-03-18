<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@welcome');

Auth::routes();



Route::group(['middleware' => ['auth','no.back']], function() {
	Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home-announcement', 'HomeController@announcement');
    Route::get('/home-enrolled-subjects', 'HomeController@getEnrolledSubject');
    Route::get('/home-handled-subjects', 'HomeController@getSubjectHandled');
    Route::get('/home-handled-class', 'HomeController@getMyClassBySubjectSection');
    Route::get('/home-student-handouts', 'HomeController@getHandouts');
});

Route::group(['prefix' => 'handouts', 'middleware' => ['auth','no.back']], function() {
    Route::get('/', 'HandoutsController@index');
    Route::get('/get-class', 'HandoutsController@getClass');
    Route::get('/get-handouts', 'HandoutsController@getHandouts');
    Route::post('/save-handouts', 'HandoutsController@save');
    Route::post('/delete-handouts', 'HandoutsController@delete');
});

Route::group(['prefix' => 'global', 'middleware' => ['auth','no.back']], function() {
    Route::get('/get-programs', 'GlobalController@getPrograms');
    Route::get('/get-departments', 'GlobalController@getDepartments');
    Route::get('/get-sections', 'GlobalController@getSections');
    Route::get('/get-school-year', 'GlobalController@getSchoolYear');
});

Route::group(['prefix' => 'monitoring', 'middleware' => ['auth','no.back','admin']], function() {
    Route::get('user-logs','Monitoring\LogsController@user_log_view');
    Route::get('get-user-logs','Monitoring\LogsController@user_logs');

    Route::get('activity-logs','Monitoring\LogsController@activity_log_view');
    Route::get('get-activity-logs','Monitoring\LogsController@activity_logs');

    Route::get('handouts','Monitoring\HandoutsController@index');
    Route::get('get-handouts','Monitoring\HandoutsController@getHandouts');
    
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth','no.back']], function() {
    Route::get('change-password','Settings\ChangePasswordController@index');
    Route::get('check-password','Settings\ChangePasswordController@checkOldPassword');
    Route::post('save-password','Settings\ChangePasswordController@save');
});

Route::group(['prefix' => 'maintenance', 'middleware' => ['auth','no.back','admin']], function() {
    Route::get('accounts','Maintenance\AccountsController@index');
    Route::get('get-accounts','Maintenance\AccountsController@getAccounts');
    Route::get('get-account-info','Maintenance\AccountsController@getInfo');
    Route::post('save-accounts','Maintenance\AccountsController@save');
    Route::post('delete-accounts','Maintenance\AccountsController@delete');
    Route::post('enable-accounts','Maintenance\AccountsController@enable');
    Route::post('upload-accounts','Maintenance\AccountsController@upload');

    Route::get('school-year','Maintenance\SchoolYearController@index');
    Route::get('get-school-year','Maintenance\SchoolYearController@getSchoolYear');
    Route::post('save-school-year','Maintenance\SchoolYearController@save');
    Route::post('delete-school-year','Maintenance\SchoolYearController@delete');

    Route::get('programs','Maintenance\ProgramsController@index');
    Route::get('get-programs','Maintenance\ProgramsController@getPrograms');
    Route::post('save-programs','Maintenance\ProgramsController@save');
    Route::post('delete-programs','Maintenance\ProgramsController@delete');

    Route::get('sections','Maintenance\SectionsController@index');
    Route::get('get-sections','Maintenance\SectionsController@getSections');
    Route::post('save-sections','Maintenance\SectionsController@save');
    Route::post('delete-sections','Maintenance\SectionsController@delete');

    Route::get('subjects','Maintenance\SubjectsController@index');
    Route::get('get-subjects','Maintenance\SubjectsController@getSubjects');
    Route::post('save-subjects','Maintenance\SubjectsController@save');
    Route::post('delete-subjects','Maintenance\SubjectsController@delete');

    Route::get('departments','Maintenance\DepartmentsController@index');
    Route::get('get-departments','Maintenance\DepartmentsController@getDepartments');
    Route::post('save-departments','Maintenance\DepartmentsController@save');
    Route::post('delete-departments','Maintenance\DepartmentsController@delete');

    Route::get('announcement','Maintenance\AnnouncementController@index');
    Route::get('get-announcement','Maintenance\AnnouncementController@getAnnouncement');
    Route::post('save-announcement','Maintenance\AnnouncementController@save');
    Route::post('delete-announcement','Maintenance\AnnouncementController@delete');
});

Route::group(['prefix' => 'transaction', 'middleware' => ['auth','no.back','admin']], function() {
    Route::get('subject-to-section','Transaction\SubjectToSectionController@index');
    Route::get('get-assigned-subject','Transaction\SubjectToSectionController@getAssigned');
    Route::get('get-subject-and-section','Transaction\SubjectToSectionController@getSectionSubject');
    Route::post('save-assign-subjects','Transaction\SubjectToSectionController@save');
    Route::post('delete-assign-subjects','Transaction\SubjectToSectionController@delete');

    Route::get('subject-handle','Transaction\SubjectHandleController@index');
    Route::get('get-subject-handle-teachers','Transaction\SubjectHandleController@getTeachers');
    Route::get('get-subject-handle-subjects','Transaction\SubjectHandleController@getSubjects');
    Route::get('get-subject-handle-sections','Transaction\SubjectHandleController@getSections');
    Route::get('get-subject-handle','Transaction\SubjectHandleController@getHandled');
    Route::post('save-subject-handle','Transaction\SubjectHandleController@save');

    Route::get('enroll-students','Transaction\EnrollStudentController@index');
    Route::get('get-enrolled','Transaction\EnrollStudentController@getEnrolled');
    Route::get('get-enrolled-subjects', 'Transaction\EnrollStudentController@getSubjects');
    Route::get('get-enrolled-students', 'Transaction\EnrollStudentController@getStudents');
    Route::post('save-enroll-students', 'Transaction\EnrollStudentController@save');
});

Route::group(['prefix' => 'activities', 'middleware' => ['auth','no.back','teacher']], function() {
    Route::get('quizzes','Activities\QuizController@index');
    Route::get('get-quizzes','Activities\QuizController@getQuizzes');
    Route::get('get-quiz-details','Activities\QuizController@getQuizDetails');
    Route::get('get-subject-handle','Activities\QuizController@getSubjectHandle');
    Route::post('save-quizzes','Activities\QuizController@save');
    Route::post('delete-quizzes','Activities\QuizController@delete');

    Route::get('give-quiz','Activities\GiveQuizController@index');
    Route::get('get-given-quiz','Activities\GiveQuizController@getGivenQuiz');
    Route::get('give-quiz-subjects','Activities\GiveQuizController@getSubjectHandle');
    Route::get('give-quiz-sections','Activities\GiveQuizController@getSectionHandle');
    Route::get('give-quiz-quizzes','Activities\GiveQuizController@getQuiz');
    Route::post('save-give-quizzes','Activities\GiveQuizController@save');
    Route::post('delete-give-quizzes','Activities\GiveQuizController@delete');

    Route::get('homeworks','Activities\HomeworkController@index');
    Route::get('get-homeworks','Activities\HomeworkController@getHomeworks');
    Route::get('get-subject-handle-hw','Activities\HomeworkController@getSubjectHandle');
    Route::post('save-homeworks','Activities\HomeworkController@save');
    Route::post('delete-homeworks','Activities\HomeworkController@delete');

    Route::get('give-homeworks','Activities\GiveHomeworkController@index');
    Route::get('get-give-homeworks','Activities\GiveHomeworkController@getGivenHomework');
    Route::get('give-homework-subjects','Activities\GiveHomeworkController@getSubjectHandle');
    Route::get('give-homework-sections','Activities\GiveHomeworkController@getSectionHandle');
    Route::get('give-homework-homeworks','Activities\GiveHomeworkController@getHomework');
    Route::post('save-give-homeworks','Activities\GiveHomeworkController@save');
    Route::post('delete-give-homeworks','Activities\GiveHomeworkController@delete');

    Route::get('homeworks-answer','Activities\HomeworkAnswerController@index');
    Route::get('get-answer','Activities\HomeworkAnswerController@getAnswers');
    Route::get('get-files','Activities\HomeworkAnswerController@getAttachments');

    Route::get('quiz-grade','Activities\QuizGradeController@index');
    Route::get('get-given-quiz-grade','Activities\QuizGradeController@GivenQuiz');
    Route::get('get-quiz-grade-details','Activities\QuizGradeController@QuizGradeDetails');
    Route::get('quiz-grade-print','Activities\QuizGradeController@print');

    Route::get('item-analysis','Activities\ItemAnalysisController@index');
    Route::get('get-given-quiz-analysis','Activities\ItemAnalysisController@GivenQuiz');
    Route::get('get-quiz-analysis-details','Activities\ItemAnalysisController@AnalysisDetails');
    Route::get('item-analysis-print','Activities\ItemAnalysisController@print');
});

Route::group(['prefix' => 'student-activities', 'middleware' => ['auth','no.back','student']], function() {
    Route::get('quizzes','StudentActivities\QuizController@index');
    Route::get('get-pending-quizzes','StudentActivities\QuizController@getPending');
    Route::get('get-finished-quizzes','StudentActivities\QuizController@getFinished');
    Route::get('get-quiz-details','StudentActivities\QuizController@getQuizDetails');
    Route::get('take-quiz','StudentActivities\QuizController@takeQuiz');
    Route::get('view-quiz-results','StudentActivities\QuizController@viewResults');
    Route::post('submit-quiz','StudentActivities\QuizController@submitQuiz');

    

    Route::get('homeworks','StudentActivities\HomeworkController@index');
    Route::get('get-pending-homeworks','StudentActivities\HomeworkController@getPending');
    Route::get('get-finished-homeworks','StudentActivities\HomeworkController@getFinished');
    Route::get('get-details-homeworks','StudentActivities\HomeworkController@getDetails');
    Route::get('get-answer-homeworks','StudentActivities\HomeworkController@getAnswer');
    Route::post('save-homeworks','StudentActivities\HomeworkController@save');

    
});

Route::group(['prefix' => 'student-activities', 'middleware' => ['auth','no.back']], function() {
    Route::get('get-count-quizzes','StudentActivities\QuizController@getQuizCount');
    Route::get('get-count-homeworks','StudentActivities\HomeworkController@getHomeworkCount');
});

Route::group(['prefix' => 'parent-activities', 'middleware' => ['auth','no.back','parent']], function() {
    Route::get('quizzes','ParentActivities\QuizController@index');
    Route::get('get-pending-quizzes','ParentActivities\QuizController@getPending');
    Route::get('get-finished-quizzes','ParentActivities\QuizController@getFinished');
    Route::get('get-quiz-details','ParentActivities\QuizController@getQuizDetails');
    Route::get('view-quiz-results','ParentActivities\QuizController@viewResults');

    Route::get('homeworks','ParentActivities\HomeworkController@index');
    Route::get('get-pending-homeworks','ParentActivities\HomeworkController@getPending');
    Route::get('get-finished-homeworks','ParentActivities\HomeworkController@getFinished');
});

Route::group(['prefix' => 'messages', 'middleware' => ['auth','no.back']], function() {
    Route::get('inbox','MessageController@index');
    Route::get('conversation/{id}','MessageController@conversation');
    Route::get('get-all-recipients','MessageController@getRecipients');
    Route::get('get-messages','MessageController@getMessages');
    Route::get('get-conversations','MessageController@getConversations');
    Route::post('send-message','MessageController@SendMessage');
    Route::post('send-reply','MessageController@SendReply');
    Route::post('delete','MessageController@delete');
});
