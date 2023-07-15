@extends('layouts.app')
@section('content')
    @extends('layouts.productcard')
    @if (session()->has('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="container">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                {{-- <div class="card p-0" style="display:inline-block; height:600px; width: 600px">
                    <div class="card-image"> --}}
                        <img src="{{ url($products->product_img) }}" style="height:600px;width:600px" />
                    {{-- </div>
                </div> --}}
            </div>
            <div class="col-md-6">
                <h1 class="display-5 fw-bolder">{{ $products->product_name }}</h1>
                <h4>₱ {{ $products->price }}</h4><br>
                <div class="small mb-1">Colorway: {{ $products->colorway }}</div>
                <div class="small mb-1">Brand: {{ $products->brand_name }}</div>
                <div class="small mb-1">Size: {{ $products->size }}</div><br>
                @if ($products->brand_name == 'Nike')
                    <p class="lead">Stay fresh and light on your feet all day long with Nike's breathable shoe designs.
                        Engineered with strategically placed ventilation and lightweight materials, our shoes promote
                        airflow, keeping your feet cool and dry in any environment.</p>
                @elseif($products->brand_name == 'Adidas')
                    <p class="lead">Crafted with premium materials and advanced textiles, Adidas shoes offer unparalleled
                        durability and flexibility. Embrace the freedom to move naturally, as our shoes adapt to your every
                        stride, allowing you to push boundaries and break records.</p>
                @elseif($products->brand_name == 'Vans')
                    <p class="lead">With their classic silhouettes and minimalist charm, Vans shoes are the epitome of
                        timeless design. From the skatepark to casual hangouts, these versatile kicks effortlessly
                        complement any outfit, reflecting your unique personality and taste.</p>
                @endif
                <div class="d-flex">
                    <form action="{{ url('addcart', $products->id) }}" method="POST">
                        @csrf
                        <input type="hidden" value="1" name="quantity">
                        <button class="btn btn-outline-dark flex-shrink-0" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Add to cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
