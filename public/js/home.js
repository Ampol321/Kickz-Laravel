$(function () {
    $('.alert').fadeOut(3000, function () {
        $(this).remove();
    });
});

$(document).on('click', '.addtocart', function (e) {
    e.preventDefault();
    let id = $(this).attr('data-id')
    $.ajax({
        url: `/addcart/${id}`,
        type: "POST",
        dataType: "json",
        // data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('.for-alert').prepend(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Product Added to Cart!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`);

            let product = data.product_in_cart;

            if (!product) {
                let cartCount = $('.cart-count');
                let newCartCount = parseInt(cartCount.text()) + 1;
                cartCount.text(newCartCount);
            }

            $('.alert').fadeOut(5000, function () {
                $(this).remove();
            });
        },
        error: function (error) {
            alert("error");
        },
    })
})