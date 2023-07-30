let dataTable = $('#paymentsTable').DataTable({
    ajax: {
        url: '/api/paymentTable',
        dataSrc: ''
    },
    responsive: true,
    autoWidth: false,
    dom: 'Bfrtip',
    buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5'
    ],
    columns: [{
        data: 'id'
    },
    {
        data: null,
        render: function (data) {
            return `<img src="${data.payment_img}" width="100" height="100" />`;
        }
    },

    {
        data: 'payment_name'
    },

    {
        data: null,
        render: function (data) {
            return `<button type="button" data-bs-toggle="modal" data-bs-target="#paymentModal" data-id="${data.id}" class="btn btn-primary edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" data-id="${data.id}" class="btn btn-danger btn-delete delete">
                    <i class="fas fa-trash" style="color:white"></i>
                </button>`;
        }
    }
    ]
});

$('#create').on('click', function () {
    $('#update').hide();
    $('#save').show();
    $('#paymentModal *').prop('disabled', false);
    $.ajax({
        url: "/api/payment/create",
        type: "GET",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#paymentForm').trigger('reset')
        },
        error: function (error) {
            alert("error");
        },
    })
})

$('#save').on('click', function () {
    let formData = new FormData($('#paymentForm')[0]);
    // for (var pair of formData.entries()) {
    //     console.log(pair[0] + ', ' + pair[1]);
    // }
    $('#paymentModal *').prop('disabled', true);
    $.ajax({
        url: "/api/payment/store",
        type: "POST",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#paymentModal *').prop('disabled', false);
            $('#paymentForm').trigger('reset')
            $('#paymentModal').modal('hide')

            $('.for-alert').prepend(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Payment Successfully Created!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`);

            $('.alert').fadeOut(5000, function () {
                $(this).remove();
            });
        },
        error: function (error) {
            // alert("error");
            $('#paymentModal *').prop('disabled', false);
        },
    })
})

$(document).on('click', 'button.edit', function () {
    $('#save').hide();
    $('#update').show();
    $('input[name="document[]"]').remove();

    let id = $(this).attr('data-id');
    $('#update').attr('data-id', id);
    $.ajax({
        url: `/api/payment/edit/${id}`,
        type: "GET",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#payment_name').val(data.payment.payment_name);
        },
        error: function (error) {
            alert("error");
        },
    })
});

$('#update').on('click', function (event) {
    let id = $(this).attr('data-id');
    let formData = new FormData($('#paymentForm')[0]);
    // for (var pair of formData.entries()) {
    //     console.log(pair[0] + ', ' + pair[1]);
    // }
    formData.append('_method', 'PUT');
    $('#paymentModal *').prop('disabled', true);

    $.ajax({
        url: `/api/payment/update/${id}`,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "json",
        success: function (data, status) {
            $('#paymentModal').modal("hide");
            $('#paymentModal *').prop('disabled', false);
            $('#paymentModal').trigger("reset");
            $('input[name="document[]"]').remove();

            $('.for-alert').prepend(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Payment Successfully Updated!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`);

            $('.alert').fadeOut(5000, function () {
                $(this).remove();
            });
        },
        error: function (error) {
            console.log(error.responseJSON.errors);
            alert("error");
            $('#paymentModal *').prop('disabled', false);
        }
    })
})

$(document).on('click', 'button.delete', function () {
    let id = $(this).attr("data-id");
    $.confirm({
        title: 'Delete Payment',
        content: 'Do you want to delete this payment?',
        buttons: {
            confirm: function () {
                $.ajax({
                    url: `/api/payment/delete/${id}`,
                    type: 'DELETE',
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.for-alert').prepend(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Payment Successfully Deleted!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                        $('.alert').fadeOut(5000, function () {
                            $(this).remove();
                        });
                        $(`td:contains(${id})`).closest('tr').fadeOut(5000, function () {
                            $(this).remove();
                        });
                    },
                    error: function () {
                        alert('error')
                    }
                })
            },

            cancel: function () {
            },
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const paymentForm = document.getElementById('paymentForm');
    const updateBtn = document.getElementById('update');
    const saveBtn = document.getElementById('save');

    updateBtn.addEventListener('click', validateForm);
    saveBtn.addEventListener('click', validateForm);

    function validateForm() {
        const paymentImg = document.getElementById('payment_img');
        const paymentName = document.getElementById('payment_name');

        if (paymentImg.files.length === 0) {
            alert('Please select an image.');
            return;
        }

        if (paymentName.value.trim() === '') {
            alert('Please enter a payment name.');
            return;
        }

        paymentForm.submit();
    }
});
