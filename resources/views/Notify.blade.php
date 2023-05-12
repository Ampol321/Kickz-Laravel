@component('mail::message')
@extends('layouts.tables')
An order has been requested by: <b>{{$name}}</b></br>
<center>
<table class="table" style="width:500px">
    <tr>
        <td></td>
        <td><b>Product Name:</b></td>
        <td><b>Quantity:</b></td>
        <td><b>Price:</b></td>
    </tr>
    @foreach($cart as $carts)
    <tr>
        <td style="vertical-align: middle;"><img src="{{url($carts->product_img)}}" width="100px" height="100px"></td>
        <td style="vertical-align: middle;">{{$carts->product_name}}</td>
        <td style="vertical-align: middle;">{{$carts->quantity}}</td>
        <td style="vertical-align: middle;">₱ {{$carts->price}}</td>
    </tr>
    @endforeach
</table>
</center><br><br>

Total Price: ₱{{$totalprice}}<br>
Shipping Address: {{$processorders->shipping_address}}<br>
Shipment Choice: {{$processorders->shipment_name}}<br>
Shipment Cost: {{$processorders->shipment_cost}}<br>
Payment Used: {{$processorders->payment_name}}<br>
Credit Card Information: {{$processorders->credit_card}}<br>
<h3>Order Total: <b>₱{{$totalprice + $processorders->shipment_cost}}</b><h3>
<center>
@component('mail::button', ['url' => url('/approve-order', $processorders->id)])
    Approve
@endcomponent
</center>

@endcomponent