/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 21);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/quiz_grade.js":
/*!******************************************!*\
  !*** ./resources/js/pages/quiz_grade.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  getQuizzes();
  $('#tbl_quiz_body').on('click', '.btn_view_grade', function () {
    $('.loading').show();
    var items = '';
    $('#quiz_id').val($(this).attr('data-quiz_id'));
    $('#quiz_title').val($(this).attr('data-quiz_title'));
    $('#quiz_type').val($(this).attr('data-quiz_type'));
    $('#subject').val($(this).attr('data-subject'));
    $('#section').val($(this).attr('data-section'));
    $('#section_id').val($(this).attr('data-section_id'));
    $('#subject_id').val($(this).attr('data-subject_id'));
    $.ajax({
      url: '../../activities/get-quiz-grade-details',
      type: 'GET',
      dataType: 'JSON',
      data: {
        quiz_id: $(this).attr('data-quiz_id'),
        quiz_title: $(this).attr('data-quiz_title'),
        quiz_type: $(this).attr('data-quiz_type'),
        subject: $(this).attr('data-subject'),
        section: $(this).attr('data-section'),
        section_id: $(this).attr('data-section_id'),
        subject_id: $(this).attr('data-subject_id')
      }
    }).done(function (data, textStatus, xhr) {
      var qgr = data.qg_results;
      var qgi = data.qg_item;

      if (qgr !== null && qgr !== null) {
        $('#td_teacher').html(qgr.teacher);
        $('#td_subject').html(qgr.subject);
        $('#td_section').html(qgr.section);
        $('#td_quiz_title').html(qgr.quiz_title);
        $('#td_quiz_type').html(qgr.quiz_type);
        $('#td_start_date').html(qgr.start_date);
        $('#td_due_date').html(qgr.due_date);
        $('#td_max_attempt').html(qgr.max_attempt);
        $('#td_timer').html(qgr.timer);
        $('#td_late_submission').html(qgr.late_submission);
        $('#td_instruction').html(qgr.instruction);

        if (qgi.length < 1) {
          $('#tbl_details_body').html('<tr><td colspan="5">No student have taken this quiz</td></tr>');
        } else {
          $.each(qgi, function (i, x) {
            items += '<tr>' + '<td>' + x.student + '</td>' + '<td>' + x.attempt_no + '</td>' + '<td>' + x.total_points + '/' + x.max_score + '</td>' + '<td>' + x.grade_percent + '</td>' + '<td>' + x.remarks + '</td>' + '</tr>';
          });
          $('#tbl_details_body').html(items);
        }

        $('#quiz_grade_modal').modal('show');
      } else {
        msg("Quiz Details seems deleted.", "failed");
      }
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Quiz Details: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
  $('#btn_print').on('click', function () {
    var link = '../../activities/quiz-grade-print?quiz_id=' + $('#quiz_id').val() + '&&quiz_title=' + $('#quiz_title').val() + '&&quiz_type=' + $('#quiz_type').val() + '&&subject=' + $('#subject').val() + '&&section=' + $('#section').val() + '&&section_id=' + $('#section_id').val() + '&&subject_id=' + $('#subject_id').val();
    window.open(link, '_blank');
  });
});

function getQuizzes() {
  $('.loading').show();
  $.ajax({
    url: '../../activities/get-given-quiz-grade',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    QuizzesTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Quizzes: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function QuizzesTable(arr) {
  $('#tbl_quiz').dataTable().fnClearTable();
  $('#tbl_quiz').dataTable().fnDestroy();
  $('#tbl_quiz').dataTable({
    data: arr,
    responsive: true,
    scrollX: true,
    columnDefs: [{
      targets: 0,
      sortable: false
    }],
    order: [[1, "desc"]],
    columns: [{
      data: function data(_data) {
        return '<button class="btn btn-sm btn-primary btn_view_grade" data-quiz_id="' + _data.quiz_id + '"' + 'data-quiz_title="' + _data.quiz_title + '"' + 'data-quiz_type="' + _data.quiz_type + '"' + 'data-subject="' + _data.subject + '"' + 'data-section="' + _data.section + '"' + 'data-section_id="' + _data.section_id + '"' + 'data-subject_id="' + _data.subject_id + '"' + '>' + '<i class="fa fa-edit"></i>' + '</button>';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'subject'
    }, {
      data: 'section'
    }, {
      data: 'quiz_title'
    }, {
      data: 'quiz_type'
    }]
  });
}

/***/ }),

/***/ 21:
/*!************************************************!*\
  !*** multi ./resources/js/pages/quiz_grade.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\quiz_grade.js */"./resources/js/pages/quiz_grade.js");


/***/ })

/******/ });