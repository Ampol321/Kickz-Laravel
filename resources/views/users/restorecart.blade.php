@extends('layouts.app')
@section('content')
    <div class="card-body">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{session()->get('message')}}
            </div>
        @endif
        @foreach($cart as $carts)
            @if ($carts->deleted_at != null)
            <div class = "container" style="border:1px solid #cecece;">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td>Product Image:</td>
                        <td>Product Name:</td>
                        <td>Quantity:</td>
                        <td>Price:</td>
                        <td>Action:</td>
                    </tr>

                    {{-- @foreach($cart as $carts) --}}
                    <tr>
                        <td><img src="{{url($carts->product_img)}}" width="100px" height="100px"></td>
                        <td>{{$carts->product_name}}</td>
                        <td>{{$carts->quantity}}</td>
                        <td>{{$carts->price}}</td>
                        <td>
                            <a href="{{url('restore',$carts->id)}}"><i class="fa fa-undo" style="color:blue"></i></a>
                        </td>
                    </tr>
                    {{-- @endforeach --}}
                </table>
            </div>
            </div>
        @else
            <center><h1>No Products inside</h1></center>
        @endif
        @endforeach
    </div>
@endsection