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
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/school_year.js":
/*!*******************************************!*\
  !*** ./resources/js/pages/school_year.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  checkAllCheckboxesInTable('.check_all', '.check_yr');
  getSchoolYear();
  $('#frm_schoolyear').on('submit', function (e) {
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
      getSchoolYear();
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('School Year: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
  $('#from').on('change', function () {
    var from = parseInt($(this).val());
    var to = from + 1;
    $('#to').val(to);
  });
  $('#tbl_schoolyear_body').on('click', '.btn_edit_schoolyear', function () {
    $('#id').val($(this).attr('data-id'));
    $('#from').val($(this).attr('data-from'));
    $('#to').val($(this).attr('data-to'));
  });
  $('#btn_delete').on('click', function () {
    var ids = [];
    var msgs = 'Do you want to delete this School year?';
    $('#tbl_schoolyear_body').find('.check_yr:checked').each(function (index, el) {
      ids.push($(this).val());
    });

    if (ids.length > 1) {
      msgs = 'Do you want to delete these School years?';
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
            url: '../../maintenance/delete-school-year',
            type: 'POST',
            dataType: 'JSON',
            data: {
              ids: ids
            }
          }).done(function (data, textStatus, xhr) {
            getSchoolYear();
            msg(data.msg, data.status);
          }).fail(function (xhr, textStatus, errorThrown) {
            msg('School Year: ' + errorThrown, 'error');
          });
        }
      }
    });
  });
});

function getSchoolYear() {
  $.ajax({
    url: '../../maintenance/get-school-year',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    SchoolYearTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('School Year: ' + errorThrown, 'error');
  });
}

function SchoolYearTable(arr) {
  $('#tbl_schoolyear').dataTable().fnClearTable();
  $('#tbl_schoolyear').dataTable().fnDestroy();
  $('#tbl_schoolyear').dataTable({
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
    order: [[4, "desc"]],
    columns: [{
      data: function data(_data) {
        return '<input type="checkbox" class="check_yr" value="' + _data.id + '">';
      }
    }, {
      data: function data(_data2) {
        return '<button class="btn btn-sm btn-primary btn_edit_schoolyear"' + 'data-id="' + _data2.id + '" ' + 'data-from="' + _data2.from + '" ' + 'data-to="' + _data2.to + '" >' + '<i class="fa fa-edit"></i>' + '</button>';
      }
    }, {
      data: 'from'
    }, {
      data: 'to'
    }, {
      data: 'created_at'
    }]
  });
}

/***/ }),

/***/ 10:
/*!*************************************************!*\
  !*** multi ./resources/js/pages/school_year.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\school_year.js */"./resources/js/pages/school_year.js");


/***/ })

/******/ });