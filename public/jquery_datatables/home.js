$(function () {
    $(".card").hide().each(function (index) {
        $(this).delay(200 * index).fadeIn(2000);
    });

    $('.alert').fadeOut(5000, function () {
        $(this).remove();
    });
});
