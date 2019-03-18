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
/******/ 	return __webpack_require__(__webpack_require__.s = 23);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/homework_answers.js":
/*!************************************************!*\
  !*** ./resources/js/pages/homework_answers.js ***!
  \************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  StudentAnswers();
  $('#tbl_homeworks_body').on('click', '.btn_view_answer', function () {
    $('.loading').show();
    var td_subject = $(this).attr('data-subject');
    var td_title = $(this).attr('data-title');
    var td_question = $(this).attr('data-question');
    var td_answer = $(this).attr('data-answer');
    $.ajax({
      url: '../../activities/get-files',
      type: 'GET',
      dataType: 'JSON',
      data: {
        hw_id: $(this).attr('data-hw_id'),
        student_id: $(this).attr('data-student_id')
      }
    }).done(function (data, textStatus, xhr) {
      var htmlfiles = '';
      $.each(data.attachments, function (i, x) {
        htmlfiles += '<a href="../' + x.fullpath + '" class="btn btn-sm btn-secondary" target="_blank">' + x.filename + '</a>';
      });
      $('#attachments').html(htmlfiles);
      var st_att = '';
      $.each(data.student_att, function (i, x) {
        st_att += '<a href="../' + x.fullpath + '" class="btn btn-sm btn-secondary" target="_blank">' + x.filename + '</a>';
      });
      $('#student_attachments').html(st_att);
      $('#td_subject').html(td_subject);
      $('#td_title').html(td_title);
      $('#td_question').html(td_question);
      $('#td_answer').html(td_answer);
      $('#homework_modal').modal('show');
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Answers: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
});

function StudentAnswers() {
  $('.loading').show();
  $.ajax({
    url: '../../activities/get-answer',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    AnswerTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Answers: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function AnswerTable(arr) {
  $('#tbl_homeworks').dataTable().fnClearTable();
  $('#tbl_homeworks').dataTable().fnDestroy();
  $('#tbl_homeworks').dataTable({
    data: arr,
    responsive: true,
    scrollX: true,
    order: [[4, "desc"]],
    columns: [{
      data: function data(_data) {
        return '<button class="btn btn-sm btn-primary btn_view_answer"' + 'data-hw_id="' + _data.hw_id + '"' + 'data-question="' + _data.question + '"' + 'data-student_id="' + _data.student_id + '"' + 'data-title="' + _data.title + '"' + 'data-section="' + _data.section + '"' + 'data-subject="' + _data.subject + '"' + 'data-student_name="' + _data.student_name + '"' + 'data-answer="' + _data.answer + '"' + 'data-date_submitted="' + _data.date_submitted + '"' + '>' + '<i class="fa fa-edit"></i>' + '</button>';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'subject'
    }, {
      data: 'section'
    }, {
      data: 'title'
    }, {
      data: 'student_name'
    }, {
      data: 'date_submitted'
    }]
  });
}

/***/ }),

/***/ 23:
/*!******************************************************!*\
  !*** multi ./resources/js/pages/homework_answers.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\homework_answers.js */"./resources/js/pages/homework_answers.js");


/***/ })

/******/ });