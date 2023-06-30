@extends('layouts.app')
@section('content')
    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
                {{ session()->get('message') }}
            </div>
        @endif

        @if ($searchResults->count())
            @extends('layouts.productcard')
            <center>
                @foreach ($searchResults as $product)
                    <div class="card p-0" style="display:inline-block; height:600px; width: 480px">
                        <div class="card-image">
                            <img src="{{ url($product->product_img) }}" alt="">
                        </div>
                        <div class="card-footer">
                            <h4>{{ $product->product_name }}</h4>
                            <h6>Color: {{ $product->colorway }}</h6>
                            <h6>Brand: {{ $product->brand_name }}</h6>
                            <h6>Size: {{ $product->size }}</h6>
                        </div>
                        <div class="card-content d-flex flex-column align-items-center">
                            <h4>₱ {{ $product->price }}</h4></br>
                            <form action="{{ url('addcart', $product->id) }}" method="POST">
                                @csrf
                                <input type="hidden" value="1" name="quantity">
                                <input class="btn btn-dark" type="submit" value="Add to Cart"></br>
                            </form>
                        </div>
                    </div>
                @endforeach
            </center>
        @else
            <h1 style="text-align: center;  padding:20%;">No Results Found</h1>
        @endif
    </div>
@endsection
