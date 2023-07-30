@extends('layouts.tables')
@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success ">
                <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
                {{ session()->get('message') }}
            </div>
        @endif

        <center>
            <h1><b>Products</b></h1>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                Stored Images</button>
            <button {{-- href=" url('/product/create')" --}} id="create" type="button" data-bs-toggle="modal"
                class="btn btn-success btn-sm" data-bs-target="#productModal"> Add Product </a>
        </center></br>

        <div class="container" style="width: 1000px;">
            @if (empty($productChart))
                <div></div>
            @else
                <div>{!! $productChart->container() !!}</div>
                {!! $productChart->script() !!}
            @endif
        </div></br>

        <div class="container" style="width: 1100px; padding:5px; border:2px solid #cecece;">
            {{-- {{ $dataTable->table() }}
            {{ $dataTable->scripts() }} --}}
            <table id="productsTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Colorway</th>
                        <th>Size</th>
                        <th>Price</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>Stocks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for images-->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Product Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        use App\Models\product;
                        $products = product::all();
                    @endphp

                    @foreach ($products as $product)
                        @php
                            $images = explode(',', $product->product_img);
                        @endphp
                        @foreach ($images as $item)
                            <img src="{{ $item }}" style="height:100px; width:100px">
                        @endforeach
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for CRUD-->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLongTitle">Product</h5>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#productModal" class="close"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="productForm" action="{{ url('product') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}

                        <label>Image:</label></br>
                        <input type="file" name="product_img[]" multiple id="product_img" class="form-control" required>
                        </br>

                        <label>Product Name:</label></br>
                        <input type="text" name="product_name" id="product_name" class="form-control" required>
                        </br>

                        <div class="row">
                            <div class="col">
                                <label for="brand__name">Brand Name:</label>
                            </div>
                            <div class="col">
                                <label>Colorway:</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <select id="brandSelect" class="form-select form-control" name="brand_id">
                                    <option id="brandOption" value="" selected required>Select Brand</option>
                                </select>
                            </div>

                            <div class="col">
                                <input type="text" name="colorway" id="colorway" class="form-control">
                            </div>
                        </div></br>

                        <div class="row">
                            <div class="col-6">
                                <label for="type_name">Type:</label>
                            </div>

                            <div class="col-3">
                                <label>Size:</label>
                            </div>

                            <div class="col-3">
                                <label>Price:</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <select id="typeSelect" class="form-select form-control" name="type_id">
                                    <option id="typeOption" value="" selected required>Select Type</option>
                                </select>
                            </div>

                            <div class="col-3">
                                <input type="number" name="size" id="size" class="form-control" required>
                            </div>

                            <div class="col-3">
                                <input type="text" name="price" id="price" class="form-control" required>
                            </div>
                        </div></br>

                        <label id="stockLabel">Stock</label></br>
                        <input type="text" name="stock" id="stock" class="form-control">
                    </form>
                </div>

                <div class="modal-footer">
                    <button id="update" type="button" class="btn btn-dark" data-dismiss="modal">Update</button>
                    <button id="save" type="button" class="btn btn-success">Save</button>
                </div>

            </div>
        </div>
    </div>

    {{-- @error('product_img')
        <small><i>*{{ $message }}</i></small>
    @enderror --}}

    {{-- @error('product_name')
        <small><i>*{{ $message }}</i></small>
    @enderror --}}

    {{-- @error('brand_id')
        <small><i>*{{ $message }}</i></small>
    @enderror --}}

    {{-- @error('colorway')
        <small><i>*{{ $message }}</i></small>
    @enderror --}}

    {{-- @error('type_id')
        <small><i>*{{ $message }}</i></small>
    @enderror --}}

    {{-- @error('size')
        <small><i>*{{ $message }}</i></small>
    @enderror --}}

    {{-- @error('price')
        <small><i>*{{ $message }}</i></small>
    @enderror --}}
    <script src="{{ asset('jquery_datatables/products.js') }}"></script>
@endsection
