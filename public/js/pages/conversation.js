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
/******/ 	return __webpack_require__(__webpack_require__.s = 31);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/conversation.js":
/*!********************************************!*\
  !*** ./resources/js/pages/conversation.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  getConvo($('#subject_id').val());
  $('#msg_file').bind('change', function () {
    var files = $(this)[0].files;
    var htmlfiles = '';
    var label = '';
    $.each(files, function (i, x) {
      htmlfiles += '<button class="btn btn-sm btn-secondary" disabled>' + x.name + ' | size: ' + bytesToSize(x.size) + '</button>';
      label += x.name + '; ';
    });
    $('#reply_attachments').html(htmlfiles);
    $('#file_label').html(label);
  });
  $('#frm_message').on('submit', function (e) {
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
      getConvo($('#subject_id').val());
      $('#file_label').html('');
      $('#reply_attachments').html('');
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Send Message: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
});

function getConvo(id) {
  $('.loading').show();
  $.ajax({
    url: '../../messages/get-conversations',
    type: 'GET',
    dataType: 'JSON',
    data: {
      subject_id: id
    }
  }).done(function (data, textStatus, xhr) {
    var htmlfiles = '';
    $.each(data.attachments, function (i, x) {
      htmlfiles += '<a href="../../' + x.fullpath + '" class="btn btn-sm btn-secondary" target="_blank">' + x.filename + '</a>';
    });
    $('#attachments').html(htmlfiles);
    $('#user_to').val(data.recipient_id);
    MessageTable(data.msg, data.current_user);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Get Message: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function MessageTable(arr, current_user) {
  var messages = '';
  $.each(arr, function (i, x) {
    if (x.user_id_from == current_user) {
      messages += '<div class="row">' + '<div class="col-md-6"></div>' + '<div class="col-md-6">' + '<small>' + x.user_from + ' | ' + x.date_sent + '</small>' + '<div class="alert alert-success" role="alert">' + x.message + '</div>' + '</div>' + '</div>';
    } else {
      messages += '<div class="row">' + '<div class="col-md-6">' + '<small>' + x.user_from + ' | ' + x.date_sent + '</small>' + '<div class="alert alert-secondary" role="alert">' + x.message + '</div>' + '</div>' + '<div class="col-md-6"></div>' + '</div>';
    }
  });
  $('#convo_msg').html(messages);
  var messageBody = document.querySelector('#convo_msg');
  messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
}

/***/ }),

/***/ 31:
/*!**************************************************!*\
  !*** multi ./resources/js/pages/conversation.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\conversation.js */"./resources/js/pages/conversation.js");


/***/ })

/******/ });