@extends('layouts.receipt')
<h1 style="text-align: center;">KICKZ</h1>
<p style="text-align: center;">Customer's Receipt</p>
<p style="text-align: center;">---------------------------------------------------------------------------</p>

<h3 style="text-align: center;"><b>Order No# {{ $processorders->id }}</b></h3>
<table class="table" style="width:80%; margin: 0 auto;">
    <tr>
        <td style="text-align: center;"><b>Product Ordered</b></td>
        <td style="text-align: center;"><b>Quantity</b></td>
        <td style="text-align: center;"><b>Price</b></td>
    </tr>
    @foreach ($cart as $carts)
        <tr>
            <td>{{ $carts->product_name }}</td>
            <td style="text-align: center;">{{ $carts->quantity }}</td>
            <td>Php.{{ $carts->price }}</td>
        </tr>
    @endforeach
</table><br>

<div class="informations">
    <p style="text-align: justify;">Total Price:<b>Php.{{ $totalprice }}</b></p>
    <p style="text-align: justify;">Shipping Address:<b>{{ $processorders->shipping_address }}</b></p>
    <p style="text-align: justify;">Shipment Choice:<b>{{ $processorders->shipment_name }}</b></p>
    <p style="text-align: justify;">Shipment Cost:<b>Php.{{ $processorders->shipment_cost }}</b></p>
    <p style="text-align: justify;">Payment Used:<b>{{ $processorders->payment_name }}</b></p>
    <p style="text-align: center;">---------------------------------------------------------------------------</p>
    <h3 style="text-align: right;">Order Total: <b>Php.{{ $totalprice + $processorders->shipment_cost }}</b>
        <h3>
</div>
