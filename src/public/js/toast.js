/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************!*\
  !*** ./resources/js/toast.js ***!
  \*******************************/
var Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  color: 'white',
  showConfirmButton: false,
  showCloseButton: true,
  timer: 6000,
  timerProgressBar: true,
  didOpen: function didOpen(toast) {
    toast.addEventListener('mouseenter', Swal.stopTimer);
    toast.addEventListener('mouseleave', Swal.resumeTimer);
  }
});
var id = '#result-toast';

if ($(id).length) {
  Toast.fire({
    template: id,
    background: $(id).data('background-color')
  });
}
/******/ })()
;