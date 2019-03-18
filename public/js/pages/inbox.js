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
/******/ 	return __webpack_require__(__webpack_require__.s = 32);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/inbox.js":
/*!*************************************!*\
  !*** ./resources/js/pages/inbox.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

$(function () {
  getRecipients();
  getMessages();
  checkAllCheckboxesInTable('.check_all', '.check_msg');
  $('#btn_new').on('click', function () {
    $('#message_modal').modal('show');
  });
  $('#msg_file').bind('change', function () {
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
      getMessages();
      $('#file_label').html('');
      $('#attachements').html('');
      $('#message_modal').modal('hide');
    }).fail(function (xhr, textStatus, errorThrown) {
      msg('Send Message: ' + errorThrown, 'error');
    }).always(function () {
      $('.loading').hide();
    });
  });
  $('#btn_delete').on('click', function () {
    var ids = [];
    var msgs = 'Do you want to delete this Message?';
    $('#tbl_messages_body').find('.check_msg:checked').each(function (index, el) {
      ids.push($(this).val());
    });

    if (ids.length > 1) {
      msgs = 'Do you want to delete these Messages?';
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
            url: '../../messages/delete',
            type: 'POST',
            dataType: 'JSON',
            data: {
              ids: ids
            }
          }).done(function (data, textStatus, xhr) {
            clear('.clear');
            getMessages();
            msg(data.msg, data.status);
          }).fail(function (xhr, textStatus, errorThrown) {
            msg('Accounts: ' + errorThrown, 'error');
          });
        }
      }
    });
  });
});

function getMessages() {
  $('.loading').show();
  $.ajax({
    url: '../../messages/get-messages',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    MessageTable(data);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Get Message: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

function MessageTable(arr) {
  $('#tbl_messages').dataTable().fnClearTable();
  $('#tbl_messages').dataTable().fnDestroy();
  $('#tbl_messages').dataTable({
    data: arr,
    responsive: true,
    scrollX: true,
    order: [[4, "desc"]],
    columns: [{
      data: function data(_data) {
        return '<input type="checkbox" class="check_msg" value="' + _data.subject_id + '">';
      },
      searchable: false,
      orderable: false
    }, {
      data: function data(_data2) {
        return '<a href="../../messages/conversation/' + _data2.subject_id + '" class="btn btn-sm btn-primary btn_convo"' + 'data-subject_id="' + _data2.subject_id + '"' + '>' + '<i class="fa fa-comments"></i>' + '</a>';
      },
      searchable: false,
      orderable: false
    }, {
      data: 'user_from'
    }, {
      data: 'subject_msg'
    }, {
      data: 'date_sent'
    }]
  });
}

function getRecipients() {
  $('.loading').show();
  var options = '<option value=""></option>';
  $('#recipients').html(options);
  $.ajax({
    url: '../../messages/get-all-recipients',
    type: 'GET',
    dataType: 'JSON'
  }).done(function (data, textStatus, xhr) {
    $.each(data, function (i, x) {
      options += '<option value="' + x.id + '">' + x.fullname + '</option>';
    });
    $('#recipients').html(options);
  }).fail(function (xhr, textStatus, errorThrown) {
    msg('Recipients: ' + errorThrown, 'error');
  }).always(function () {
    $('.loading').hide();
  });
}

/***/ }),

/***/ 32:
/*!*******************************************!*\
  !*** multi ./resources/js/pages/inbox.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\lightoflife2019\resources\js\pages\inbox.js */"./resources/js/pages/inbox.js");


/***/ })

/******/ });