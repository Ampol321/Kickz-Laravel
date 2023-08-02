const ctx = document.getElementById('paymentChart');

$.ajax({
    url: '/api/paymentChart',
    type: 'GET',
    dataType: "json",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (data) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    data: Object.values(data),
                    borderWidth: 1,
                    borderColor: 'rgba(0, 0, 0, 1)',
                    backgroundColor: [
                        'rgba(79, 121, 66, 0.5)',
                        'rgba(18, 96, 204, 0.5)',
                        'rgba(41, 197, 246, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(201, 203, 207, 0.5)'
                    ],
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                plugins: {
                    datalabels: {
                        color: 'black',
                        labels: {
                            title: {
                                font: {
                                    size: 15,
                                },
                            },
                        }
                    }
                },
                maintainAspectRatio: false,
                responsive: true,
            }
        });
    },
    error: function () {

    }
})

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

$(function () {
    $("#paymentForm").validate({
        errorElement: "small",
        rules: {
            payment_img: {
                required: true,
            },
            payment_name: {
                required: true,
                minlength: 2,
            },
        },
        messages: {
            payment_img: {
                required: "Please select an image.",
            },
            payment_name: {
                required: "Please enter a payment name.",
                minlength: "Payment name must be at least 2 characters.",
            },
        },
        submitHandler: function (form) {
        },
    });

    $("#save").click(function () {
        $("#paymentForm").submit();
    });

    $("#close").click(function () {
        $("#paymentForm").find("small").remove();
    });
});

$('#import').on('click', function () {

    let formData = new FormData($('#importForm')[0]);

    $.ajax({
        url: '/api/payment/import',
        type: "POST",
        contentType: false,
        processData: false,
        data: formData,
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            dataTable.ajax.reload()
            $('#importModal').modal('hide');
            $('#buttonClose').trigger('click');
            $('#importForm').trigger('reset');
            $('.for-alert').prepend(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Successfully Imported!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
            $('.alert').fadeOut(5000, function () {
                $(this).remove();
            });
        },
        error: function () {
            $('#importForm').trigger('reset');
            $('#importModal').modal("hide");
            $('.for-alert').prepend(`
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Please Import Excel Only
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
            $('.alert').fadeOut(5000, function () {
                $(this).remove();
            });
        }
    })
})

$("#importFile").on("change", function (e) {
    let filename = e.target.files[0].name;
    $('#labelFile').html(filename);
})
