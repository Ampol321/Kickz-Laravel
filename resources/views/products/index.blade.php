@extends('layouts.tables')
@extends('layouts.app')
@section('content')
    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
                {{ session()->get('message') }}
            </div>
        @endif
        <center>
            <h1><b>Products</b></h1>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                Stored Images</button>
            <a href="{{ url('/product/create') }}" class="btn btn-success btn-sm"> Add Product </a>
        </center></br>
        <div class="container" style="width: 1000px; border:2px solid #cecece;">
            {{ $dataTable->table() }}
            {{ $dataTable->scripts() }}
        </div>
    </div>

    <!-- Modal -->
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
@endsection
