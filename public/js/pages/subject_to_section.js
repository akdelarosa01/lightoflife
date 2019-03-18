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
/******/ 	return __webpack_require__(__webpack_require__.s = 16);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/subject_to_section.js":
/*!**************************************************!*\
  !*** ./resources/js/pages/subject_to_section.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  getAssigned();
  SectionTable([]);
  SubjectTable([]);
  checkAllCheckboxesInTable('.check_all_sections', '.check_section');
  checkAllCheckboxesInTable('.check_all_subjects', '.check_subject');
  checkAllCheckboxesInTable('.check_all_sec', '.check_sec');
  programDropdown();
  $('#btn_assign').on('click', function () {
    $('#assign_subject_modal').modal('show');
  });
  $('#assign_subject_modal').on('shown.bs.modal', function (e) {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  });
  $('#program').on('change', function () {
    getSubjectSection($(this).val());
  });
  $('#frm_subtosec').on('submit', function (e) {
    e.preventDefault();
    $('.loading').show();
    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      dataType: 'JSON',
      data: $(this).serialize()
    }).done(function (data, textStatus, xhr) {
      msg(data.msg, data.status);
      clear('.clear');
      SectionTable([]);
      SubjectTable([]);
      getAssigned();
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Save Assigned subjects: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
  $('#btn_delete').on('click', function () {
    var ids = [];
    var msgs = 'Do you want to delete all subject to this section?';
    $('#tbl_assigned_body').find('.check_sec:checked').each(function (index, el) {
      ids.push($(this).val());
    });

    if (ids.length > 1) {
      msgs = 'Do you want to delete all subject to this sections?';
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
            url: '../../transaction/delete-assign-subjects',
            type: 'POST',
            dataType: 'JSON',
            data: {
              ids: ids
            }
          }).done(function (data, textStatus, xhr) {
            clear('.clear');
            getAssigned();
            msg(data.msg, data.status);
          }).fail(function (xhr, textStatus, errorThrown) {
            msg('Delete Assigned Subjects: ' + errorThrown, 'error');
          });
        }
      }
    });
  });
});

function getAssigned() {
  $('.loading').show();
  $.ajax({
    url: '../../transaction/get-assigned-subject',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    AssignedTables(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Assigned subjects: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function AssignedTables(arr) {
  $('#tbl_assigned').dataTable().fnClearTable();
  $('#tbl_assigned').dataTable().fnDestroy();
  $('#tbl_assigned').dataTable({
    data: arr,
    responsive: true,
    scrollX: true,
    columnDefs: [{
      targets: 0,
      sortable: false
    }],
    order: [[3, "desc"]],
    columns: [{
      data: function data(_data) {
        return '<input type="checkbox" class="check_sec" value="' + _data.id + '">';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'program'
    }, {
      data: 'section'
    }, {
      data: 'created_at'
    }]
  });
}

function getSubjectSection(program_id) {
  $('.loading').show();
  $.ajax({
    url: '../../transaction/get-subject-and-section',
    type: 'GET',
    dataType: 'JSON',
    data: {
      program_id: program_id
    }
  }).done(function (data, textStatus, xhr) {
    SectionTable(data.sections);
    SubjectTable(data.subjects);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Subjects and Sections: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function SectionTable(arr) {
  $('#tbl_section').dataTable().fnClearTable();
  $('#tbl_section').dataTable().fnDestroy();
  $('#tbl_section').dataTable({
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

function SubjectTable(arr) {
  $('#tbl_subject').dataTable().fnClearTable();
  $('#tbl_subject').dataTable().fnDestroy();
  $('#tbl_subject').dataTable({
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
      data: function data(_data3) {
        return '<input type="checkbox" class="check_subject" name="subject[]" value="' + _data3.id + '">';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'code'
    }, {
      data: 'description'
    }]
  });
}

/***/ }),

/***/ 16:
/*!********************************************************!*\
  !*** multi ./resources/js/pages/subject_to_section.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\subject_to_section.js */"./resources/js/pages/subject_to_section.js");


/***/ })

/******/ });