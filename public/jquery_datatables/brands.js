let dataTable = $('#brandsTable').DataTable({
    ajax: {
        url: '/api/brandTable',
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
    columns: [    
    {
        data: 'id'
    },
    
    {
        data: null,
        render: function (data) {
            return `<img src="${data.img_path}" width="100" height="100" />`;
        }
    },

    {
        data: 'brand_name'
    },

    {
        data: null,
        render: function (data) {
            return `<button type="button" data-bs-toggle="modal" data-bs-target="#brandModal" data-id="${data.id}" class="btn btn-primary edit">
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
    $('#brandModal *').prop('disabled', false);
    $.ajax({
        url: "/api/brand/create",
        type: "GET",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#brandForm').trigger('reset')     
        },
        error: function (error) {
            alert("error");
        },
    })
})

$('#save').on('click', function () {
    let formData = new FormData($('#brandForm')[0]);
    // for (var pair of formData.entries()) {
    //     console.log(pair[0] + ', ' + pair[1]);
    // }
    $('#brandModal *').prop('disabled', true);
    $.ajax({
        url: "/api/brand/store",
        type: "POST",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#brandModal *').prop('disabled', false);
            $('#brandForm').trigger('reset')
            $('#brandModal').modal('hide')

            $('.for-alert').prepend(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Brand Successfully Created!
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
            $('#brandModal *').prop('disabled', false);
        },
    })
})

$(document).on('click', 'button.edit', function () {
    $('#save').hide();
    $('#update').show();
    $('input[name="document[]"]').remove();

    let id = $(this).attr('data-id');
    $('#update').attr('data-id',id);
    $.ajax({
        url: `/api/brand/edit/${id}`,
        type: "GET",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#brand_name').val(data.brand.brand_name);  
        },
        error: function (error) {
            alert("error");
        },
    })
});

$('#update').on('click', function (event) {
    let id = $(this).attr('data-id');
    let formData = new FormData($('#brandForm')[0]);
    // for (var pair of formData.entries()) {
    //     console.log(pair[0] + ', ' + pair[1]);
    // }
    formData.append('_method', 'PUT');
    $('#brandModal *').prop('disabled', true);

    $.ajax({
        url: `/api/brand/update/${id}`,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: "json",
        success: function (data, status) {
            $('#brandModal').modal("hide");
            $('#brandModal *').prop('disabled', false);
            $('#brandModal').trigger("reset");
            $('input[name="document[]"]').remove();

            $('.for-alert').prepend(`
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Brand Successfully Updated!
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
            $('#brandModal *').prop('disabled', false);
        }
    })
})

$(document).on('click', 'button.delete', function () {
    let id = $(this).attr("data-id");
    $.confirm({
        title: 'Delete Brand',
        content: 'Do you want to delete this brand?',
        buttons: {
            confirm: function () {
                $.ajax({
                    url: `/api/brand/delete/${id}`,
                    type: 'DELETE',
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.for-alert').prepend(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Brand Successfully Deleted!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                        $('.alert').fadeOut(5000, function () {
                            $(this).remove();
                        });
                        $(`td:contains(${id})`).closest('tr').fadeOut(5000, function(){
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
    const brandForm = document.getElementById('brandForm');
    const updateBtn = document.getElementById('update');
    const saveBtn = document.getElementById('save');

    updateBtn.addEventListener('click', validateForm);
    saveBtn.addEventListener('click', validateForm);

    function validateForm() {
        const brandImg = document.getElementById('img_path');
        const brandName = document.getElementById('brand_name');

        if (brandImg.files.length === 0) {
            alert('Please select an image.');
            return;
        }

        if (brandName.value.trim() === '') {
            alert('Please enter a brand name.');
            return;
        }

        brandForm.submit();
    }
});