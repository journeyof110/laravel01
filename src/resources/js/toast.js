const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  color: 'white',
  showConfirmButton: false,
  showCloseButton: true,
  timer: 6000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

const id = '#result-toast';
if($(id).length){
  Toast.fire({
    template: id,
    background: $(id).data('background-color'),
  });
}