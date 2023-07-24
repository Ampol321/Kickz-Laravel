@extends('layouts.app')
@section('content')
    @extends('layouts.carousel')
    @if (session()->has('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
            {{ session()->get('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
            {{ session()->get('error') }}
        </div>
    @endif

    <div id="slider">
        <figure>
            {{-- <img src="http://127.0.0.1:8000/storage/images/banner1.png"> --}}
            <img src="http://127.0.0.1:8000/storage/images/banner2.png">
            <img src="http://127.0.0.1:8000/storage/images/banner3.png">
            <img src="http://127.0.0.1:8000/storage/images/banner4.png">
        </figure>
    </div>

    <center>
        <h1><b>Our Products</b></h1>
        <h6 style="margin:0% 30% 0% 30%">Welcome to Kickz! We are thrilled to offer you a wide range of shoes from
            Adidas, Nike and Vans that will not only make you look stylish but also feel comfortable .</h6><br><br>
        <center>

            @extends('layouts.productcard')
            <center>
                @foreach ($products as $product)
                    <div class="card p-0" style="display:inline-block; height:600px; width: 480px">
                        <div class="card-image">
                            <img src="{{ url($product->product_img) }}" alt="">
                        </div>
                        <div class="card-footer">
                            <h3 style="color:rgb(167, 94, 94)">{{ $product->product_name }}</h3>
                            <h6>Colorway: {{ $product->colorway }}</h6>
                            <h6>Brand: {{ $product->brand_name }}</h6>
                            <h6>Size: {{ $product->size }}</h6>
                        </div>
                        <div class="card-content d-flex flex-column align-items-center">
                            <h4>₱ {{ $product->price }}</h4></br>
                            <form action="{{ url('addcart', $product->id) }}" method="POST">
                                @csrf
                                <input type="hidden" value="1" name="quantity">
                                {{-- <input class="btn btn-dark" type="submit" value="Add to Cart"></br> --}}
                                <div class="d-flex pt-1">
                                    <a href="{{ url('/product-detail', $product->id) }}" class="btn btn-success"
                                        style="margin-right:5px"> Details </a>
                                    <input class="btn btn-dark" type="submit" value="Add to Cart">
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </center>

            <div class="d-flex justify-content-center" style="margin-top:20px">
                {{ $products->links() }}
            </div>

            <script>
                $(function() {
                    $(".card").hide().each(function(index) {
                        $(this).delay(200 * index).fadeIn(2000);
                    });
                });
            </script>
        @endsection
