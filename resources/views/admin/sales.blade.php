@extends('layouts.tables')
@extends('layouts.app')
@section('content')
    <center>
        <h1><b>Kickz Sales</b></h1>
    </center></br>

    <div class="container" style="width: 1000px;">
        @if (empty($salesChart))
            <div></div>
        @else
            <div>{!! $salesChart->container() !!}</div>
            {!! $salesChart->script() !!}
        @endif
    </div></br>

    <div class="d-flex justify-content-center">
        <form action="{{ url('/date-range') }}" method="GET">
            <div class="row">
                <div class="col">
                    <label>Start</label>
                    <input id="date1" class="form-control mr-sm-2" type="date" placeholder="Search..." name="date1">
                </div>
                <div class="col">
                    <label>End</label>
                    <input id="date2" class="form-control mr-sm-2" type="date" placeholder="Search..."
                        name="date2">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col d-flex justify-content-center">
                  <button type="submit" class="btn btn-dark btn-sm">Search</button>
                </div>
              </div>
        </form>
    </div><br>

    <div style="margin: 0 auto; width: 65%;">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Shipment Type</th>
                    <th scope="col">Shipment Cost</th>
                    <th scope="col">Payment Type</th>
                    <th scope="col">Date Shipped</th>
                    <th scope="col">Items Price</th>
                    <th scope="col">Order Total</th>
                </tr>
            </thead>
            @php
                $total = 0;
            @endphp
            @foreach ($orders as $order)
                <tbody>
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->shipment_name }}</td>
                        <td>₱ {{ $order->shipment_cost }}</td>
                        <td>{{ $order->payment_name }}</td>
                        <td>{{ $order->date_shipped }}</td>
                        <td>₱ {{ $sales->where('id', $order->id)->first()->totalprice }}</td>
                        <td>₱ {{ $sales->where('id', $order->id)->first()->totalprice + $order->shipment_cost }}
                        </td>
                    </tr>
                </tbody>
                @php
                    $total += $sales->where('id', $order->id)->first()->totalprice + $order->shipment_cost;
                @endphp
            @endforeach
        </table>
        <div class="d-flex justify-content-end">
            <h3>Total Sales: ₱ {{ $total }}</h3>
        </div>
    </div>
@endsection
