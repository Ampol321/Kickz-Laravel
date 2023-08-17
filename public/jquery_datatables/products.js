const ctx = document.getElementById('productChart');

$.ajax({
    url: '/api/productChart',
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
                    label: 'Best Seller Shoe Product',
                    data: Object.values(data),
                    borderColor: 'rgba(0, 0, 0, 1)',
                    borderWidth: 1,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(255, 205, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(201, 203, 207, 0.5)'
                    ],
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
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
            },


        });
    },
    error: function () {

    }
})

let dataTable = $('#productsTable').DataTable({
    ajax: {
        url: '/api/productTable',
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
            return `<img src="${data.product_img}" width="100" height="100" />`;
        }
    },

    {
        data: 'product_name'
    },
    {
        data: 'colorway'
    },
    {
        data: 'size'
    },
    {
        data: 'price'
    },
    {
        data: 'brand.brand_name'
    },
    {
        data: 'type.type_name'
    },
    {
        data: 'stock.stock'
    },
    {
        data: null,
        render: function (data) {
            return `<button type="button" data-bs-toggle="modal" data-bs-target="#productModal" data-id="${data.id}" class="btn btn-primary edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" data-id="${data.id}" class="btn btn-danger btn-delete delete">
                    <i class="fas fa-trash" style="color:white"></i>
                </button>`;
        }
    }
    ]
});

function clearSelect() {
    $('#brandSelect option:not(#brandOption)').remove()
    $('#typeSelect option:not(#typeOption)').remove()
}

$('#create').on('click', function () {
    $('#stock').hide();
    $('#stockLabel').hide();
    $('#update').hide();
    $('#save').show();
    clearSelect()
    $.ajax({
        url: "/api/product/create",
        type: "GET",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $.each(data.brands, function (key, value) {
                $('#brandSelect').append($('<option>').attr("value", value.id).html(value.brand_name))

            })
            $.each(data.types, function (key, value) {
                $('#typeSelect').append($('<option>').attr("value", value.id).html(value.type_name))
            })
            $('#productForm').trigger('reset')
        },
        error: function (error) {
            alert("error");
        },
    })
})

$('#save').on('click', function () {
    let formData = new FormData($('#productForm')[0]);
    // for (var pair of formData.entries()) {
    //     console.log(pair[0] + ', ' + pair[1]);
    // }
    // $('#productModal *').prop('disabled', true);
    $.ajax({
        url: "/api/product/store",
        type: "POST",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#productModal *').prop('disabled', false);
            $('#productForm').trigger('reset')
            $('#productModal').modal('hide')
            dataTable.ajax.reload();

            $('.for-alert').prepend(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Product Successfully Created!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`);

            $('.alert').fadeOut(5000, function () {
                $(this).remove();
            });
        },
        error: function (error) {
            alert("error");
        },
    })
})

$(document).on('click', 'button.edit', function () {
    $('#save').hide();
    $('#update').show();
    $('#stock').show();
    $('#stockLabel').show();
    $('input[name="document[]"]').remove();

    let id = $(this).attr('data-id');
    $('#update').attr('data-id', id);
    $.ajax({
        url: `/api/product/edit/${id}`,
        type: "GET",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            let brand = $('#brandSelect');
            let type = $('#typeSelect');

            clearSelect()

            $('#product_name').val(data.product.product_name);
            brand.append($('<option>').attr({ 'selected': true }).val(data.product.brand_id).html(data.product.brand_name));
            $('#colorway').val(data.product.colorway);
            type.append($('<option>').attr({ 'selected': true }).val(data.product.type_id).html(data.product.type_name));
            $('#size').val(data.product.size);
            $('#price').val(data.product.price);
            $('#stock').val(data.stocks.stock);

            $.each(data.brands, function (key, value) {
                brand.append($('<option>').val(value.id).html(value.brand_name))
            })

            $.each(data.types, function (key, value) {
                type.append($('<option>').val(value.id).html(value.type_name))
            })
        },
        error: function (error) {
            alert("error");
        },
    })
});

$('#update').on('click', function (event) {
    let id = $(this).attr('data-id');
    let formData = new FormData($('#productForm')[0]);
    for (var pair of formData.entries()) {
        console.log(pair[0] + ', ' + pair[1]);
    }

    formData.append('_method', 'PUT');

    $('#productModal *').prop('disabled', true);

    $.ajax({
        url: `/api/product/update/${id}`,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "json",
        success: function (data, status) {
            $('#productModal').modal("hide");
            $('#productModal *').prop('disabled', false);
            $('#productModal').trigger("reset");
            $('input[name="document[]"]').remove();
            dataTable.ajax.reload();

            $('.for-alert').prepend(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Product Successfully Updated!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`);

            $('.alert').fadeOut(5000, function () {
                $(this).remove();
            });
            // $('#productsTable').DataTable().ajax.reload();

        },
        error: function (error) {
            console.log(error.responseJSON.errors);
            alert("error");
        }
    })
})

$(document).on('click', 'button.delete', function () {
    let id = $(this).attr("data-id");
    $.confirm({
        title: 'Delete Product',
        content: 'Do you want to delete this product?',
        buttons: {
            confirm: function () {
                $.ajax({
                    url: `/api/product/delete/${id}`,
                    type: 'DELETE',
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.for-alert').prepend(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Product Successfully Deleted!
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
                        // dataTable.ajax.reload();
                    },
                    error: function () {
                        alert('error')
                    }
                })
            },

            cancel: function () {
                // $.alert('Cancelled!');
            },
        }
    });
});

$(function () {
    $("#productForm").validate({
        errorElement: "small",
        rules: {
            product_img: {
                required: true,
            },
            product_name: {
                required: true,
                minlength: 2,
            },
            brand_id: {
                required: true,
            },
            colorway: {
                required: true,
            },
            type_id: {
                required: true,
            },
            size: {
                required: true,
                number: true,
            },
            price: {
                required: true,
                number: true,
            },
        },
        messages: {
            product_img: {
                required: "Please select an image.",
            },
            product_name: {
                required: "Please enter a product name.",
                minlength: "Product name must be at least 2 characters.",
            },
            brand_id: {
                required: "Please select a brand.",
            },
            colorway: {
                required: "Please enter the colorway.",
            },
            type_id: {
                required: "Please select a type.",
            },
            size: {
                required: "Please enter a valid size.",
                number: "Please enter a valid number for size.",
            },
            price: {
                required: "Please enter the price.",
                number: "Please enter a valid price.",
            },
        },
        submitHandler: function (form) {
        },
    });

    $("#save").click(function () {
        $("#productForm").submit();
    });

    $("#close").click(function () {
        $("#productForm").find("small").remove();
    });
});

$('#import').on('click', function () {

    let formData = new FormData($('#importForm')[0]);

    $.ajax({
        url: '/api/product/import',
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