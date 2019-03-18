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
/******/ 	return __webpack_require__(__webpack_require__.s = 17);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/subject_handle.js":
/*!**********************************************!*\
  !*** ./resources/js/pages/subject_handle.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

subs_arr = [];
$(function () {
  getTeachers();
  $('#tbl_teachers_body').on('click', '.btn_edit', function () {
    $('#fullname_label').html($(this).attr('data-fullname'));
    $('#user_id').val($(this).attr('data-id'));
    $('#id_number').val($(this).attr('data-id_number'));
    $('#dept_id').val($(this).attr('data-dept_id'));
    $('#fullname').val($(this).attr('data-fullname'));
    getHandles($(this).attr('data-id'), $(this).attr('data-dept_id'));
    getSubjects($(this).attr('data-dept_id'));
    SectionTable([]);
    $('#subject_handle_modal').modal('show');
  });
  $('#subject_handle_modal').on('shown.bs.modal', function (e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  });
  $('#subject').on('change', function () {
    $('#subj_id').val($(this).val());
    $('#program_id').val($(this).find('option:selected').attr('data-program_id'));
    $('#program').val($(this).find('option:selected').attr('data-program'));
    getSections([{
      id: $(this).val(),
      dept_id: $(this).find('option:selected').attr('data-dept_id'),
      program_id: $(this).find('option:selected').attr('data-program_id')
    }]);
  });
  $('#frm_subhandle').on('submit', function (e) {
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
      getTeachers();
      $('#subject_handle_modal').modal('hide');
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Save handles: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
});

function getTeachers() {
  $('.loading').show();
  $.ajax({
    url: '../../transaction/get-subject-handle-teachers',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    TeachersTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Teachers: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function TeachersTable(arr) {
  $('#tbl_teachers').dataTable().fnClearTable();
  $('#tbl_teachers').dataTable().fnDestroy();
  $('#tbl_teachers').dataTable({
    data: arr,
    responsive: true,
    scrollX: true,
    columnDefs: [{
      targets: 0,
      sortable: false
    }],
    order: [[1, "asc"]],
    columns: [{
      data: function data(_data) {
        return '<button class="btn btn-sm btn-primary btn_edit" data-id="' + _data.id + '" ' + 'data-id_number="' + _data.id_number + '"' + 'data-fullname="' + _data.fullname + '"' + 'data-department="' + _data.department + '"' + 'data-dept_id="' + _data.dept_id + '">' + '<i class="fa fa-edit"></i>' + '</button>';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'id_number'
    }, {
      data: 'fullname'
    }, {
      data: 'department'
    }]
  });
}

function getSubjects(dept_id) {
  var option = '<option value=""></option>';
  $('#subject').html(option);
  $.ajax({
    url: '../../transaction/get-subject-handle-subjects',
    type: 'GET',
    dataType: 'JSON',
    data: {
      dept_id: dept_id
    }
  }).done(function (data, textStatus, xhr) {
    $.each(data.subjects, function (i, x) {
      option += '<option value="' + x.id + '" ' + 'data-dept_id="' + x.dept_id + '" ' + 'data-program_id="' + x.program_id + '"' + 'data-program="' + x.program + '"' + '>' + x.code + ' - ' + x.description + '</option>';
    });
    $('#subject').html(option);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Subjects: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function getSections(sub_data) {
  $.ajax({
    url: '../../transaction/get-subject-handle-sections',
    type: 'GET',
    dataType: 'JSON',
    data: {
      sub_data: sub_data
    }
  }).done(function (data, textStatus, xhr) {
    SectionTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Sections: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function SectionTable(arr) {
  $('#tbl_sections').dataTable().fnClearTable();
  $('#tbl_sections').dataTable().fnDestroy();
  $('#tbl_sections').dataTable({
    data: arr,
    responsive: true,
    sorting: false,
    searching: false,
    lengthChange: false,
    scrollX: true,
    columnDefs: [{
      targets: 0,
      sortable: false
    }],
    order: [[1, "asc"]],
    columns: [{
      data: function data(_data2) {
        return '<input type="checkbox" class="check_section" name="section[]" value="' + _data2.id + '">';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'program'
    }, {
      data: 'section'
    }]
  });
}

function getHandles(teacher_id, dept_id) {
  $('.loading').show();
  $.ajax({
    url: '../../transaction/get-subject-handle',
    type: 'GET',
    dataType: 'JSON',
    data: {
      id: parseInt(teacher_id),
      dept_id: parseInt(dept_id)
    }
  }).done(function (data, textStatus, xhr) {
    HandlesTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Subject Handle: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function HandlesTable(arr) {
  $('#tbl_handles').dataTable().fnClearTable();
  $('#tbl_handles').dataTable().fnDestroy();
  $('#tbl_handles').dataTable({
    data: arr,
    responsive: true,
    sorting: false,
    searching: false,
    lengthChange: false,
    scrollX: true,
    order: [[0, "asc"]],
    columns: [{
      data: 'program'
    }, {
      data: 'section'
    }, {
      data: 'subject'
    }]
  });
}

/***/ }),

/***/ 17:
/*!****************************************************!*\
  !*** multi ./resources/js/pages/subject_handle.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\subject_handle.js */"./resources/js/pages/subject_handle.js");


/***/ })

/******/ });