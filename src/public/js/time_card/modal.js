$(".btn-delete").on("click",(function(t){var a=$(this).data("daytime"),d=$(this).data("link");$("#modal-timecard-day").html(a),$(".modal-footer form").attr("action",d)}));