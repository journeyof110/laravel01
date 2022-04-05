$('.card-loading, .modal-loading').on('click', function() {
  const loading = $('#hiding-loading .overlay').hide().fadeIn(200);
  var loadingTo = '.card';
  if ($(this).attr('class').split(' ').indexOf('modal-loading') != -1) {
    loadingTo = '.modal-dialog';
  }
  $(this).parents(loadingTo).append(loading);
});