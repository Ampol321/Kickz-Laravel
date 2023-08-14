let id = $('#cartsTable').data('id')
let dataTable = $('#cartsTable').DataTable({
    ajax: {
        url: `/api/cartTable/${id}`,
        dataSrc: ''
    },
    responsive: true,
    autoWidth: false,
    // dom: 'Bfrtip',
    // buttons: [
    //     'copyHtml5',
    //     'excelHtml5',
    //     'csvHtml5',
    //     'pdfHtml5'
    // ],
    columns: [
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
        // data: null,
        data: null,
        render: function (data) {
            return `<button class="btn btn-success btn-sm increment"
            data-id="${ data.product_id }">+</button>
            &nbsp;&nbsp;${ data.quantity }&nbsp;&nbsp;
            <button class="btn btn-danger btn-sm decrement"
            data-id="${ data.product_id }">-</button>`;
        }
    },
    {
        data: 'price'
    },
    {
        data: null,
        render: function (data) {
            return `<button type="button" data-id="${data.product_id}" class="btn btn-danger btn-delete delete">
                    <i class="fas fa-trash" style="color:white"></i>
                </button>`;
        }
    }
    ]
});

document.getElementById("shipment").addEventListener("change", function () {
    if (this.value === "1") {
        document.getElementById("shipment_cost").value = "₱35.25";
    } else if (this.value === "2") {
        document.getElementById("shipment_cost").value = "₱45.67";
    } else if (this.value === "3") {
        document.getElementById("shipment_cost").value = "₱55.89";
    } else {
        document.getElementById("shipment_cost").value = "";
    }
});

document.getElementById("mySelect").addEventListener("change", function () {
    if (this.value === "2" || this.value === "3") {
        document.getElementById("otherOption").style.display = "block";
        document.getElementById("otherInput").required = true;

    } else {
        document.getElementById("otherOption").style.display = "none";
        document.getElementById("otherInput").required = false;
        document.getElementById("otherInput").name = null;
        document.getElementById("otherInput").value = "x";
    }
});

$('.alert').fadeOut(5000, function () {
    $(this).remove();
});

$(document).on('click', '.increment', function (e) {
    e.preventDefault();
    let id = $(this).data('id');
    $.ajax({
        url: `/increment/${id}`,
        type: "POST",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            dataTable.ajax.reload()
            let quantity = $('.quantity[data-id="' + id + '"]');
            let newQuantity = parseInt(quantity.text()) + 1;
            quantity.text(newQuantity);
            $('.price[data-id="' + id + '"]').text('₱ ' + data.price);
        },
        error: function (error) {
            alert("Error");
        },
    });
});

$(document).on('click', '.decrement', function (e) {
    e.preventDefault();
    let id = $(this).data('id');
    $.ajax({
        url: `/decrement/${id}`,
        type: "POST",
        dataType: "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            dataTable.ajax.reload()
            let quantity = $('.quantity[data-id="' + id + '"]');
            let newQuantity = parseInt(quantity.text()) - 1;
            quantity.text(newQuantity);
            $('.price[data-id="' + id + '"]').text('₱ ' + data.price);
        },
        error: function (error) {
            alert("Error");
        },
    });
});

$(document).on('click', 'button.delete', function () {
    let id = $(this).data('id');
    $.confirm({
        title: 'Delete Cart Item',
        content: 'Do you want to delete this item in your cart?',
        buttons: {
            confirm: function () {
                $.ajax({
                    url: `/delete/${id}`,
                    type: 'DELETE',
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.for-alert').prepend(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Cart Item Successfully Deleted!
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                        $('.alert').fadeOut(5000, function () {
                            $(this).remove();
                        });
                        // $(`td:contains(${id})`).closest('tr').fadeOut(5000, function () {
                        //     $(this).remove();
                        // });
                        dataTable.ajax.reload();
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