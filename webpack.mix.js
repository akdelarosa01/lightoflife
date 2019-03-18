const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts([
		'resources/plugins/jquery/jquery.min.js',
		'resources/plugins/bootstrap/js/bootstrap.bundle.min.js',
		'resources/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
		'resources/plugins/fastclick/lib/fastclick.js',
		// 'resources/js/template.js',
		'resources/plugins/toast/jquery.toast.js',
		'resources/plugins/datatables.net/js/jquery.dataTables.js',
		'resources/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js',
		'resources/plugins/bootbox/bootbox.all.min.js',
		'resources/js/resizer.js',
		'resources/js/helpers.js',
		'resources/js/csrf_token.js'
	], 'public/js/app.js')
   .styles([
		'resources/plugins/bootstrap/css/bootstrap.min.css',
		'resources/plugins/font-awesome/font-awesome.min.css',
		'resources/plugins/toast/jquery.toast.css',
		'resources/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css',
		// 'resources/css/master_style.css',
		// 'resources/css/skins/_all-skins.css',
		// 'resources/css/custom_styles.css'
		'resources/css/design.css',
		'resources/css/custom.css',
	], 'public/css/app.css')

   .js('resources/js/pages/home.js', 'public/js/pages/')
   .js('resources/js/pages/home_student.js', 'public/js/pages/')
   .js('resources/js/pages/home_teacher.js', 'public/js/pages/')
   .js('resources/js/pages/home_parent.js', 'public/js/pages/')

   .js('resources/js/pages/handouts.js', 'public/js/pages/')

   .js('resources/js/pages/activity_logs.js', 'public/js/pages/')
   .js('resources/js/pages/user_logs.js', 'public/js/pages/')
   .js('resources/js/pages/handouts-list.js', 'public/js/pages/')

   .js('resources/js/pages/change_password.js', 'public/js/pages/')

   .js('resources/js/pages/accounts.js', 'public/js/pages/')
   .js('resources/js/pages/school_year.js', 'public/js/pages/')
   .js('resources/js/pages/programs.js', 'public/js/pages/')
   .js('resources/js/pages/sections.js', 'public/js/pages/')
   .js('resources/js/pages/subjects.js', 'public/js/pages/')
   .js('resources/js/pages/departments.js', 'public/js/pages/')
   .js('resources/js/pages/announcement.js', 'public/js/pages/')

   .js('resources/js/pages/subject_to_section.js', 'public/js/pages/')
   .js('resources/js/pages/subject_handle.js', 'public/js/pages/')
   .js('resources/js/pages/enroll_students.js', 'public/js/pages/')

   .js('resources/js/pages/quiz.js', 'public/js/pages/')
   .js('resources/js/pages/homework.js', 'public/js/pages/')
   .js('resources/js/pages/quiz_grade.js', 'public/js/pages/')
   .js('resources/js/pages/item_analysis.js', 'public/js/pages/')
   .js('resources/js/pages/homework_answers.js', 'public/js/pages/')

   .js('resources/js/pages/give-quiz.js', 'public/js/pages/')
   .js('resources/js/pages/give-homework.js', 'public/js/pages/')

   .js('resources/js/pages/parent-quizlist.js', 'public/js/pages/')
   .js('resources/js/pages/parent-homeworklist.js', 'public/js/pages/')

   .js('resources/js/pages/student-quizlist.js', 'public/js/pages/')
   .js('resources/js/pages/student-homeworklist.js', 'public/js/pages/')

   .js('resources/js/pages/take_quiz.js', 'public/js/pages/')

   .js('resources/js/pages/conversation.js', 'public/js/pages/')
   .js('resources/js/pages/inbox.js', 'public/js/pages/');
