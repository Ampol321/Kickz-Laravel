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
            return `<button type="button" data-bs-toggle="modal" data-bs-target="#itemModal" data-id="${data.id}" class="btn btn-primary edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" data-id="${data.id}" class="btn btn-danger btn-delete delete">
                    <i class="fas fa-trash" style="color:white"></i>
                </button>`;
        }
    }
    ]
});