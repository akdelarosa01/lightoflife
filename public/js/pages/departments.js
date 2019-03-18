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
/******/ 	return __webpack_require__(__webpack_require__.s = 14);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/departments.js":
/*!*******************************************!*\
  !*** ./resources/js/pages/departments.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  checkAllCheckboxesInTable('.check_all', '.check_item');
  getDepartment();
  $('#frm_department').on('submit', function (e) {
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
      getDepartment();
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Department: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
  $('#tbl_dept_body').on('click', '.btn_edit_dept', function () {
    $('#id').val($(this).attr('data-id'));
    $('#department').val($(this).attr('data-department'));
  });
  $('#btn_delete').on('click', function () {
    var ids = [];
    var msgs = 'Do you want to delete this Department?';
    $('#tbl_dept_body').find('.check_item:checked').each(function (index, el) {
      ids.push($(this).val());
    });

    if (ids.length > 1) {
      msgs = 'Do you want to delete these Departments?';
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
            url: '../../maintenance/delete-departments',
            type: 'POST',
            dataType: 'JSON',
            data: {
              ids: ids
            }
          }).done(function (data, textStatus, xhr) {
            clear('.clear');
            getDepartment();
            msg(data.msg, data.status);
          }).fail(function (xhr, textStatus, errorThrown) {
            msg('Department: ' + errorThrown, 'error');
          });
        }
      }
    });
  });
});

function getDepartment() {
  $.ajax({
    url: '../../maintenance/get-departments',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    DepartmentTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Department: ' + errorThrown, 'error');
  });
}

function DepartmentTable(arr) {
  $('#tbl_dept').dataTable().fnClearTable();
  $('#tbl_dept').dataTable().fnDestroy();
  $('#tbl_dept').dataTable({
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
        return '<input type="checkbox" class="check_item" value="' + _data.id + '">';
      }
    }, {
      data: function data(_data2) {
        return "<button class='btn btn-sm btn-primary btn_edit_dept'" + "data-id='" + _data2.id + "' " + "data-department='" + _data2.department + "' >" + "<i class='fa fa-edit'></i>" + "</button>";
      }
    }, {
      data: 'department'
    }, {
      data: 'create_user'
    }, {
      data: 'created_at'
    }]
  });
}

/***/ }),

/***/ 14:
/*!*************************************************!*\
  !*** multi ./resources/js/pages/departments.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\departments.js */"./resources/js/pages/departments.js");


/***/ })

/******/ });