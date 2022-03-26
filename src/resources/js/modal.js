// モーダルにパラメータ渡し
$('.btn-modal').on('click', function (event) {
  var modalBody = $(this).children('.modal-body').html();
  console.log($(this).children('.modal-body').html());
  var link = $(this).data('link');
  $('#put-modal-body').html(modalBody);
  $('.modal-footer form').attr('action', link);
});