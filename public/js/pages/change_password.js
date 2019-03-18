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
/******/ 	return __webpack_require__(__webpack_require__.s = 8);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/change_password.js":
/*!***********************************************!*\
  !*** ./resources/js/pages/change_password.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  $('#frm_password').on('submit', function (e) {
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
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Change Password: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
  $('#old_password').on('change', function () {
    if ($(this).val() != '') {
      checkIfSameAsOldPassword($(this).val());
    }
  });
  $('#new_password').on('change', function () {
    if ($(this).val() != '' && $('#confirm_password').val() != '') {
      checkIfNewConfirmIsSame($(this).val(), $('#confirm_password').val());
    }
  });
  $('#confirm_password').on('change', function () {
    if ($('#new_password').val() != '' && $(this).val() != '') {
      checkIfNewConfirmIsSame($('#new_password').val(), $(this).val());
    }
  });
});

function checkIfNewConfirmIsSame(newp, confirm) {
  if (newp != confirm) {
    var errors = {
      'confirm_password': 'Password did not matched.',
      'new_password': 'Password did not matched.'
    };
    showErrors(errors);
    $('#btn_save').prop('disabled', true);
  } else {
    $('#btn_save').prop('disabled', false);
  }
}

function checkIfSameAsOldPassword(password) {
  $.ajax({
    url: '../../settings/check-password',
    type: 'GET',
    dataType: 'JSON',
    data: {
      password: password
    }
  }).done(function (data, textStatus, xhr) {
    console.log(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    var errors = xhr.responseJSON.errors;

    if (errors != undefined) {
      showErrors(errors);
    } else {
      msg('Check Password: ' + errorThrown, 'error');
    }
  });
}

/***/ }),

/***/ 8:
/*!*****************************************************!*\
  !*** multi ./resources/js/pages/change_password.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\change_password.js */"./resources/js/pages/change_password.js");


/***/ })

/******/ });