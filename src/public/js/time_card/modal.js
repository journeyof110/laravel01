/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************!*\
  !*** ./resources/js/time_card/modal.js ***!
  \*****************************************/
// モーダルにパラメータ渡し
$('.btn-delete').on('click', function (event) {
  var dayTime = $(this).data('daytime');
  var link = $(this).data('link');
  $('#modal-timecard-day').html(dayTime);
  $('.modal-footer form').attr('action', link);
});
/******/ })()
;