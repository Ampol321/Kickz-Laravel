$(function () {
    $('.alert').fadeOut(3000, function () {
        $(this).remove();
    });
});

// $(document).on('click', '.addtocart', function (e) {
//     e.preventDefault();
//     let id = $(this).attr('data-id')
//     $.ajax({
//         url: `/addcart/${id}`,
//         type: "POST",
//         dataType: "json",
//         // data: formData,
//         contentType: false,
//         processData: false,
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (data) {
//             $('.for-alert').prepend(`
//             <div class="alert alert-success alert-dismissible fade show" role="alert">
//                 Product Added to Cart!
//                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
//                     <span aria-hidden="true">&times;</span>
//                 </button>
//             </div>`);
//         },
//         error: function (error) {
//             alert("error");
//         },
//     })
// })