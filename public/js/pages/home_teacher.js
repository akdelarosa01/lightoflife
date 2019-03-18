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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/home_teacher.js":
/*!********************************************!*\
  !*** ./resources/js/pages/home_teacher.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  announcement();
  getSubjectHandled();
  $('#tbl_handled_body').on('click', '.btn_handles', function () {
    getClass($(this).attr('data-section_id'), $(this).attr('data-subject_id'));
    $('#class_modalLabel').html($(this).attr('data-subject') + ' | ' + $(this).attr('data-section'));
    $('#class_modal').modal('show');
  });
  $('#class_modal').on('shown.bs.modal', function (e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  });
});

function announcement() {
  $.ajax({
    url: '../../home-announcement',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    if (data.title != undefined) {
      $('#title').html('<i class="fa fa-exclamation-triangle"></i> ' + data.title);
      $('#announcement').html(data.announcement);
    } else {
      $('#title').html('<i class="fa fa-exclamation-triangle"></i> Announcement');
      $('#announcement').html('');
    }
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Announcement: ' + errorThrown, 'error');
  });
}

function getSubjectHandled() {
  $.ajax({
    url: '../../home-handled-subjects',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    HandledTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Subjects Handled: ' + errorThrown, 'error');
  });
}

function HandledTable(arr) {
  $('#tbl_handled').dataTable().fnClearTable();
  $('#tbl_handled').dataTable().fnDestroy();
  $('#tbl_handled').dataTable({
    data: arr,
    responsive: true,
    sorting: false,
    searching: false,
    lengthChange: false,
    scrollX: true,
    language: {
      emptyTable: "No Subjects were assigned to you."
    },
    columnDefs: [{
      targets: 0,
      sortable: false
    }],
    order: [[1, "asc"]],
    columns: [{
      data: function data(_data) {
        return '<button class="btn btn-sm btn-primary btn_handles" ' + 'data-section_id="' + _data.section_id + '"' + 'data-subject_id="' + _data.subject_id + '"' + 'data-subject="' + _data.subject + '"' + 'data-section="' + _data.section + '">' + '<i class="fa fa-eye"></i>' + '</button>';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'subject'
    }, {
      data: 'section'
    }]
  });
}

function getClass(section_id, subject_id) {
  $.ajax({
    url: '../../home-handled-class',
    type: 'GET',
    dataType: 'JSON',
    data: {
      section_id: section_id,
      subject_id: subject_id
    }
  }).done(function (data, textStatus, xhr) {
    classTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Class Handled: ' + errorThrown, 'error');
  });
}

function classTable(arr) {
  $('#tbl_class').dataTable().fnClearTable();
  $('#tbl_class').dataTable().fnDestroy();
  $('#tbl_class').dataTable({
    data: arr,
    responsive: true,
    sorting: false,
    searching: false,
    lengthChange: false,
    scrollX: true,
    language: {
      emptyTable: "No Students were enrolled in this class."
    },
    order: [[1, "asc"]],
    columns: [{
      data: 'student_id'
    }, {
      data: 'student_name'
    }]
  });
}

/***/ }),

/***/ 2:
/*!**************************************************!*\
  !*** multi ./resources/js/pages/home_teacher.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\home_teacher.js */"./resources/js/pages/home_teacher.js");


/***/ })

/******/ });