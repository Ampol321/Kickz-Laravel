const ctx = document.getElementById('shipmentChart');

$.ajax({
    url: '/api/shipmentChart',
    type: 'GET',
    dataType: "json",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (data) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    label: 'Most Used Shipment',
                    data: Object.values(data),
                    borderWidth: 1,
                    borderColor: 'rgba(0, 0, 0, 1)',
                    backgroundColor: [
                        'rgba(0, 123, 184, 0.5)',
                        'rgba(211, 0, 0, 0.5)',
                        'rgba(255, 223, 0, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(201, 203, 207, 0.5)'
                    ],
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                scales: {
                    y: {
                        ticks: {
                            display: false,
                        },
                    }
                },
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

let dataTable = $('#shipmentsTable').DataTable({
    ajax: {
        url: '/api/shipmentTable',
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
            return `<img src="${data.shipment_img}" width="100" height="100" />`;
        }
    },

    {
        data: 'shipment_name'
    },
    {
        data: 'shipment_cost'
    },
    {
        data: null,
        render: function (data) {
            return `<button type="button" data-bs-toggle="modal" data-bs-target="#shipmentModal" data-id="${data.id}" class="btn btn-primary edit">
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
        url: "/api/shipment/create",
        type: "GET",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#shipmentForm').trigger('reset')
        },
        error: function (error) {
            alert("error");
        },
    })
})

$('#save').on('click', function () {
    let formData = new FormData($('#shipmentForm')[0]);
    // for (var pair of formData.entries()) {
    //     console.log(pair[0] + ', ' + pair[1]);
    // }
    $.ajax({
        url: "/api/shipment/store",
        type: "POST",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#shipmentModal *').prop('disabled', false);
            $('#shipmentForm').trigger('reset')
            $('#shipmentModal').modal('hide')

            $('.for-alert').prepend(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Shipment Successfully Created!
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
        url: `/api/shipment/edit/${id}`,
        type: "GET",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#shipment_name').val(data.shipment.shipment_name);
            $('#shipment_cost').val(data.shipment.shipment_cost);
        },
        error: function (error) {
            alert("error");
        },
    })
});

$('#update').on('click', function (event) {
    let id = $(this).attr('data-id');
    let formData = new FormData($('#shipmentForm')[0]);
    // for (var pair of formData.entries()) {
    //     console.log(pair[0] + ', ' + pair[1]);
    // }
    formData.append('_method', 'PUT');
    $('#shipmentModal *').prop('disabled', true);

    $.ajax({
        url: `/api/shipment/update/${id}`,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "json",
        success: function (data, status) {
            $('#shipmentModal').modal("hide");
            $('#shipmentModal *').prop('disabled', false);
            $('#shipmentModal').trigger("reset");
            $('input[name="document[]"]').remove();

            $('.for-alert').prepend(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Shipment Successfully Updated!
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
            $('#shipmentModal *').prop('disabled', false);
        }
    })
})

$(document).on('click', 'button.delete', function () {
    let id = $(this).attr("data-id");
    $.confirm({
        title: 'Delete Shipment',
        content: 'Do you want to delete this shipment?',
        buttons: {
            confirm: function () {
                $.ajax({
                    url: `/api/shipment/delete/${id}`,
                    type: 'DELETE',
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.for-alert').prepend(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Shipment Successfully Deleted!
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
    $("#shipmentForm").validate({
        errorElement: "small",
        rules: {
            shipment_img: {
                required: true,
            },
            shipment_name: {
                required: true,
                minlength: 2,
            },
            shipment_cost: {
                required: true,
                number: true,
            },
        },
        messages: {
            shipment_img: {
                required: "Please select an image.",
            },
            shipment_name: {
                required: "Please enter a Shipment name.",
                minlength: "Shipment name must be at least 2 characters.",
            },
            shipment_cost: {
                required: "Please enter a Shipment cost.",
                number: "Please enter a valid Shipment cost.",
            },
        },
        submitHandler: function (form) {
        },
    });

    $("#save").click(function () {
        $("#shipmentForm").submit();
    });

    $("#close").click(function () {
        $("#shipmentForm").find("small").remove();
    });
});

$('#import').on('click', function () {

    let formData = new FormData($('#importForm')[0]);

    $.ajax({
        url: '/api/shipment/import',
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