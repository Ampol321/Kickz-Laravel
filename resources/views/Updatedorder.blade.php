@component('mail::message')
@extends('layouts.tables')

Order No# <b>{{$updatedorders->id}}</b> has requested to change its order information</br>
<center>
<table class="table" style="width:500px">
    <tr>
        <td></td>
        <td><b>Product Name:</b></td>
        <td><b>Quantity:</b></td>
        <td><b>Price:</b></td>
    </tr>
    @foreach($items as $item)
    <tr>
        <td style="vertical-align: middle;"><img src="{{url($item->product_img)}}" width="100px" height="100px"></td>
        <td style="vertical-align: middle;">{{$item->product_name}}</td>
        <td style="vertical-align: middle;">{{$item->quantity}}</td>
        <td style="vertical-align: middle;">₱ {{$item->price}}</td>
    </tr>
    @endforeach
</table>
</center><br><br>

Total Price: ₱{{$updatedtotal}}<br>
Shipping Address: {{$updatedorders->shipping_address}}<br>
Shipment Choice: {{$updatedorders->shipment_name}}<br>
Shipment Cost: {{$updatedorders->shipment_cost}}<br>
Payment Used: {{$updatedorders->payment_name}}<br>
Credit Card Information: {{$updatedorders->credit_card}}<br>
<h3>Order Total: <b>₱{{$updatedtotal + $updatedorders->shipment_cost}}</b><h3>
<center>
@component('mail::button', ['url' => url('/approve-order', $updatedorders->id)])
    Approve
@endcomponent
</center>

@endcomponent