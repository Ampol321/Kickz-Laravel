@extends('layouts.app')
@section('content')
    <div class="card-body">
        <div class="container">
            <div class="text-center">
                <h1>Search</h1>
                There are {{ $searchResults->count() }} results.
            </div>
            @foreach ($searchResults as $searchResult)
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-1"><a href="{{ $searchResult->url }}">{{ $searchResult->title }}</a></h4>
                        <div class="font-13 text-success mb-3">{{ $searchResult->url }}</div>
                    </div>
                </div>
                <hr>
            @endforeach
        </div>
    </div>

    {{-- <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
                {{ session()->get('message') }}
            </div>
        @endif

        @if ($products->count())
            @extends('layouts.productcard')
            <center>
                @foreach ($products as $product)
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
    </div> --}}
@endsection
