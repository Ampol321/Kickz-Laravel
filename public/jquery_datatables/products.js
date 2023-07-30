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
    // {
    //     data: null,
    //     render: function (data) {
    //         let createdDate = new Date(data.created_at);
    //         return createdDate.toLocaleDateString("en-US");
    //     }
    // },
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
    $('#productModal *').prop('disabled', false);
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
    $('#productModal *').prop('disabled', true);
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
            // alert("error");
            $('#productModal *').prop('disabled', false);
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
            $('#productModal *').prop('disabled', false);
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


document.addEventListener('DOMContentLoaded', function () {
    const productForm = document.getElementById('productForm');
    const updateBtn = document.getElementById('update');
    const saveBtn = document.getElementById('save');

    updateBtn.addEventListener('click', validateForm);
    saveBtn.addEventListener('click', validateForm);

    function validateForm() {
        const productImgInput = document.getElementById('product_img');
        const productNameInput = document.getElementById('product_name');
        const brandSelect = document.getElementById('brandSelect');
        const typeSelect = document.getElementById('typeSelect');
        const sizeInput = document.getElementById('size');
        const priceInput = document.getElementById('price');

        if (productImgInput.files.length === 0) {
            alert('Please select at least one image.');
            return;
        }

        if (productNameInput.value.trim() === '') {
            alert('Product Name field is required.');
            return;
        }

        if (brandSelect.value === '') {
            alert('Please select a Brand.');
            return;
        }

        if (typeSelect.value === '') {
            alert('Please select a Type.');
            return;
        }

        if (isNaN(sizeInput.value) || Number(sizeInput.value) <= 0) {
            alert('Please enter a valid Size.');
            return;
        }

        if (isNaN(priceInput.value) || Number(priceInput.value) <= 0) {
            alert('Please enter a valid Price.');
            return;
        }

        productForm.submit();
    }
});