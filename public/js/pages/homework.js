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
/******/ 	return __webpack_require__(__webpack_require__.s = 20);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/homework.js":
/*!****************************************!*\
  !*** ./resources/js/pages/homework.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  getHomeworks();
  getSubjectHandled();
  checkAllCheckboxesInTable('.check_all_homeworks', '.check_homework');
  $('#btn_create').on('click', function () {
    clear('.clear');
    $('#file_label').html('');
    $('#attachements').html('');
    $('#homework_modal').modal('show');
  });
  $('#tbl_homeworks_body').on('click', '.btn_edit_homework', function () {
    $('#hw_id').val($(this).attr('data-hw_id'));
    $('#subject').val($(this).attr('data-subject'));
    $('#title').val($(this).attr('data-title'));
    $('#question').val($(this).attr('data-question'));
    $('#points').val($(this).attr('data-points'));
    $('#homework_modal').modal('show');
  });
  $('#homework_file').bind('change', function () {
    var files = $(this)[0].files;
    var htmlfiles = '';
    var label = '';
    $.each(files, function (i, x) {
      htmlfiles += '<button class="btn btn-sm btn-secondary" disabled>' + x.name + ' | size: ' + bytesToSize(x.size) + '</button>';
      label += x.name + '; ';
    });
    $('#attachements').html(htmlfiles);
    $('#file_label').html(label);
  });
  $('#frm_homework').on('submit', function (e) {
    $('.loading').show();
    e.preventDefault();
    var data = new FormData(this);
    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      dataType: 'JSON',
      data: data,
      mimeType: "multipart/form-data",
      contentType: false,
      cache: false,
      processData: false
    }).done(function (data, textStatus, xhr) {
      // var return_data = jQuery.parseJSON(data);
      $('.loading').hide();
      clear('.clear');
      msg(data.msg, data.status);
      getHomeworks();
      $('#file_label').html('');
      $('#attachements').html('');
      $('#homework_modal').modal('hide');
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Save Homework: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
  $('#btn_delete').on('click', function () {
    var ids = [];
    var msgs = 'Do you want to delete this Homework?';
    $('#tbl_homeworks_body').find('.check_homework:checked').each(function (index, el) {
      ids.push($(this).val());
    });

    if (ids.length > 1) {
      msgs = 'Do you want to delete these Homeworks?';
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
            url: '../../activities/delete-homeworks',
            type: 'POST',
            dataType: 'JSON',
            data: {
              ids: ids
            }
          }).done(function (data, textStatus, xhr) {
            clear('.clear');
            getHomeworks();
            msg(data.msg, data.status);
          }).fail(function (xhr, textStatus, errorThrown) {
            msg('Homeworks: ' + errorThrown, 'error');
          });
        }
      }
    });
  });
});

function getHomeworks() {
  $('.loading').show();
  $.ajax({
    url: '../../activities/get-homeworks',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    HomeworksTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Activity Logs: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function HomeworksTable(arr) {
  $('#tbl_homeworks').dataTable().fnClearTable();
  $('#tbl_homeworks').dataTable().fnDestroy();
  $('#tbl_homeworks').dataTable({
    data: arr,
    responsive: true,
    scrollX: true,
    order: [[4, "desc"]],
    columns: [{
      data: function data(_data) {
        return '<input type="checkbox" class="check_homework" value="' + _data.id + '">';
      },
      searchable: false,
      orderable: false
    }, {
      data: function data(_data2) {
        return '<button class="btn btn-sm btn-primary btn_edit_homework"' + 'data-hw_id="' + _data2.id + '" ' + 'data-subject="' + _data2.subject_id + '" ' + 'data-title="' + _data2.title + '" ' + 'data-question="' + _data2.question + '" ' + 'data-points="' + _data2.points + '">' + '<i class="fa fa-edit"></i>' + '</button>';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'subject'
    }, {
      data: 'title'
    }, {
      data: 'created_at'
    }]
  });
}

function getSubjectHandled() {
  var option = '<option value=""></option>';
  $('#subject').html(option);
  $.ajax({
    url: '../../activities/get-subject-handle-hw',
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

/***/ }),

/***/ 20:
/*!**********************************************!*\
  !*** multi ./resources/js/pages/homework.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\homework.js */"./resources/js/pages/homework.js");


/***/ })

/******/ });