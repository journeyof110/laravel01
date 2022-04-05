/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./resources/js/loading.js ***!
  \*********************************/
$('.card-loading, .modal-loading').on('click', function () {
  var loading = $('#hiding-loading .overlay').hide().fadeIn(200);
  var loadingTo = '.card';

  if ($(this).attr('class').split(' ').indexOf('modal-loading') != -1) {
    loadingTo = '.modal-dialog';
  }

  $(this).parents(loadingTo).append(loading);
});
/******/ })()
;