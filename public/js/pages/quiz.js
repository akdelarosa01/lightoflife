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
/******/ 	return __webpack_require__(__webpack_require__.s = 19);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/quiz.js":
/*!************************************!*\
  !*** ./resources/js/pages/quiz.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  getQuizzes();
  getSubjectHandled();
  checkAllCheckboxesInTable('.check_all_quizzes', '.check_quiz');
  $('.MULTIPLE_CHOICE').hide();
  $('#btn_create').on('click', function () {
    clear('.clear');
    $('#quiz_items').html('');
    $('#quiz_modal').modal('show');
  });
  $('#tbl_quizzes_body').on('click', '.btn_make_quiz', function () {
    $('#quiz_modal').modal('show');
  });
  $('#quiz_type').on('change', function () {
    if ($(this).val() == 'MULTIPLE CHOICE') {
      $('.MULTIPLE_CHOICE').show();
    } else {
      $('.MULTIPLE_CHOICE').hide();
    }
  });
  $('#frm_quiz').on('submit', function (e) {
    e.preventDefault();
    $('.loading').show();
    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      dataType: 'JSON',
      data: $(this).serialize()
    }).done(function (data, textStatus, xhr) {
      clear('.clear');
      msg(data.msg, data.status);
      getQuizzes();
      $('#quiz_modal').modal('hide');
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Save Quiz: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
  $('#btn_delete').on('click', function () {
    var ids = [];
    var msgs = 'Do you want to delete this Quiz?';
    $('#tbl_quizzes_body').find('.check_quiz:checked').each(function (index, el) {
      ids.push($(this).val());
    });

    if (ids.length > 1) {
      msgs = 'Do you want to delete these Quizzes?';
    }

    bootbox.confirm({
      message: msgs,
      buttons: {
        confirm: {
          label: 'Yes',
          className: 'btn-danger'
        },
        cancel: {
          label: 'Cancel',
          className: 'btn-secondary'
        }
      },
      callback: function callback(result) {
        if (result) {
          $.ajax({
            url: '../../activities/delete-quizzes',
            type: 'POST',
            dataType: 'JSON',
            data: {
              ids: ids
            }
          }).done(function (data, textStatus, xhr) {
            clear('.clear');
            getQuizzes();
            msg(data.msg, data.status);
          }).fail(function (xhr, textStatus, errorThrown) {
            msg('Quizzes: ' + errorThrown, 'error');
          });
        }
      }
    });
  });
  $('#btn_generate').on('click', function () {
    var no_of_items = parseInt($('#no_of_items').val());
    var no_of_choices = parseInt($('#no_of_choices').val());
    var alpha = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    var count = 0;
    var qt = '<table class="table table-striped">';

    for (var i = 1; i <= no_of_items; i++) {
      qt += '<tr style="background-color: #379b2f !important">' + '<td id="question_num' + i + '" width="20%" style="color: #fff">Question #' + i + ':</td>' + '<td id="question_item' + i + '">' + '<input type="text" class="form-control form-control-sm" name="question[]">' + '</td>' + '</tr>';

      if ($('#quiz_type').val() == 'MULTIPLE CHOICE') {
        if (no_of_choices !== '' || no_of_choices !== 0) {
          var choice_field = '';

          for (var ii = 1; ii <= no_of_choices; ii++) {
            choice_field += '<tr style="background-color:#ffee75">' + '<td id="choice_num" width="20%">Choice ' + alpha[ii] + '</td>' + '<td id="choice_item">' + '<input type="text" class="form-control form-control-sm" name="choice_item[' + count + '][]">' + '</td>' + '</tr>';
          }
        } else {
          choice_field = '';
        }

        qt += choice_field;
      }

      qt += '<tr style="background-color:#2e2623">' + '<td id="answer_num" width="20%" style="color: #fff">Answer:</td>' + '<td id="answer_item">';

      if ($('#quiz_type').val() == 'TRUE OR FALSE') {
        qt += '<select type="text" class="form-control form-control-sm" name="answer_item[]">' + '<option value="TRUE">TRUE</option>' + '<option value="FALSE">FALSE</option>' + '</select>';
      } else if ($('#quiz_type').val() == 'MULTIPLE CHOICE') {
        qt += '<select type="text" class="form-control form-control-sm" name="answer_item[]">';

        if (no_of_choices !== '' || no_of_choices !== 0) {
          var answer_choice = '';

          for (var iii = 1; iii <= no_of_choices; iii++) {
            answer_choice += '<option value="' + alpha[iii] + '">' + alpha[iii] + '</option>';
          }
        }

        qt += answer_choice;
        qt += '</select>';
      } else {
        qt += '<input type="text" class="form-control form-control-sm" name="answer_item[]">';
      }

      qt += '</td>' + '</tr>' + '<tr style="background-color:#5d5d5d">' + '<td id="points_num" width="20%" style="color: #fff">Points</td>' + '<td id="points_item">' + '<input type="number" step=".01" value="0" class="form-control form-control-sm" name="points_item[]">' + '</td>' + '</tr>';
      count++;
    }

    qt += '</table>';
    $('#quiz_items').html(qt);
  });
  $('#tbl_quizzes_body').on('click', '.btn_edit_quiz', function () {
    $('#quiz_id').val($(this).attr('data-id'));
    $('#subject').val($(this).attr('data-subject_id'));
    $('#quiz_title').val($(this).attr('data-quiz_title'));
    $('#quiz_type').val($(this).attr('data-quiz_type'));
    $('#no_of_items').val($(this).attr('data-no_of_items'));
    $('#no_of_choices').val($(this).attr('data-no_of_choices'));
    getQuizDetails($(this).attr('data-id'));
    $('#quiz_modal').modal('show');
  });
});

function getQuizzes() {
  $('.loading').show();
  $.ajax({
    url: '../../activities/get-quizzes',
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
  $('#tbl_quizzes').dataTable().fnClearTable();
  $('#tbl_quizzes').dataTable().fnDestroy();
  $('#tbl_quizzes').dataTable({
    data: arr,
    responsive: true,
    scrollX: true,
    columnDefs: [{
      targets: 0,
      sortable: false
    }, {
      targets: 1,
      sortable: false
    }],
    order: [[5, "desc"]],
    columns: [{
      data: function data(_data) {
        return '<input type="checkbox" class="check_quiz" value="' + _data.id + '">';
      },
      searchable: false,
      orderable: false
    }, {
      data: function data(_data2) {
        return '<button class="btn btn-sm btn-primary btn_edit_quiz" data-id="' + _data2.id + '"' + 'data-subject_id="' + _data2.subject_id + '"' + 'data-quiz_title="' + _data2.quiz_title + '"' + 'data-quiz_type="' + _data2.quiz_type + '"' + 'data-no_of_items="' + _data2.no_of_items + '"' + 'data-no_of_choices="' + _data2.no_of_choices + '"' + '>' + '<i class="fa fa-edit"></i>' + '</button>';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'subject'
    }, {
      data: 'quiz_title'
    }, {
      data: 'quiz_type'
    }, {
      data: 'created_at'
    }]
  });
}

function getSubjectHandled() {
  var option = '<option value=""></option>';
  $('#subject').html(option);
  $.ajax({
    url: '../../activities/get-subject-handle',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    $.each(data, function (i, x) {
      option += '<option value="' + x.id + '">' + x.subject + '</option>';
    });
    $('#subject').html(option);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Subjects: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function getQuizDetails(id) {
  $('.loading').show();
  $.ajax({
    url: '../../activities/get-quiz-details',
    type: 'GET',
    dataType: 'JSON',
    data: {
      quiz_id: id
    }
  }).done(function (data, textStatus, xhr) {
    var no_of_items = parseInt($('#no_of_items').val());
    var no_of_choices = parseInt($('#no_of_choices').val());
    var alpha = ['', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    var items = data.items;
    var choices = data.choices;
    var count = 0;
    var qt = '<table class="table table-striped">';
    $.each(items, function (i, x) {
      qt += '<tr style="background-color: #379b2f !important">' + '<td id="question_num' + x.question_num + '" width="20%" style="color: #fff">Question #' + x.question_num + ':</td>' + '<td id="question_item' + x.question_num + '">' + '<input type="text" class="form-control form-control-sm" name="question[]" value="' + x.question + '">' + '</td>' + '</tr>';

      if (x.quiz_type == 'MULTIPLE CHOICE') {
        if (no_of_choices !== '' || no_of_choices !== 0) {
          var choice_field = '';
          $.each(choices, function (ii, xx) {
            if (xx.quiz_item_id == x.id) {
              choice_field += '<tr style="background-color:#ffee75">' + '<td id="choice_num" width="20%">Choice ' + xx.choice + '</td>' + '<td id="choice_item">' + '<input type="text" class="form-control form-control-sm" name="choice_item[' + count + '][]" value="' + xx.choice_desc + '">' + '</td>' + '</tr>';
            }
          });
        } else {
          choice_field = '';
        }

        qt += choice_field;
      }

      qt += '<tr style="background-color:#2e2623">' + '<td id="answer_num" width="20%" style="color: #fff">Answer:</td>' + '<td id="answer_item">';

      if (x.quiz_type == 'TRUE OR FALSE') {
        if (x.answer == 'TRUE') {
          qt += '<select type="text" class="form-control form-control-sm" name="answer_item[]">' + '<option value="TRUE" selected>TRUE</option>' + '<option value="FALSE">FALSE</option>' + '</select>';
        } else {
          qt += '<select type="text" class="form-control form-control-sm" name="answer_item[]">' + '<option value="TRUE">TRUE</option>' + '<option value="FALSE" selected>FALSE</option>' + '</select>';
        }
      } else {
        qt += '<input type="text" class="form-control form-control-sm" name="answer_item[]" value="' + x.answer + '">';
      }

      qt += '</td>' + '</tr>' + '<tr style="background-color:#5d5d5d">' + '<td id="points_num" width="20%" style="color: #fff">Points</td>' + '<td id="points_item">' + '<input type="number" step=".01" class="form-control form-control-sm" name="points_item[]" value="' + x.points + '">' + '</td>' + '</tr>';
      count++;
      console.log(x.points);
    });
    qt += '</table>';
    $('#quiz_items').html(qt);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Get Quiz Details: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

/***/ }),

/***/ 19:
/*!******************************************!*\
  !*** multi ./resources/js/pages/quiz.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\quiz.js */"./resources/js/pages/quiz.js");


/***/ })

/******/ });