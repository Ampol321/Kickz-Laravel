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

        @if ($cart->count())
            <center>
                <h1><b>Shopping Cart</b></h1>
            </center></br>

            <div style="margin: 0 auto; width: 65%;">
                <div class="rounded" style="width: 1000px; border:2px solid #cecece;">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th></th>
                                <th><b>Product Name:</b></th>
                                <th><b>Quantity:</b></th>
                                <th><b>Price:</b></th>
                                <th><b>Action:</b></th>
                            </tr>
                            </thead>
                            @foreach ($cart as $carts)
                                <tr>
                                    <td style="vertical-align: middle;"><img src="{{ url($carts->product_img) }}"
                                            width="100px" height="100px"></td>
                                    <td style="vertical-align: middle;">{{ $carts->product_name }}</td>
                                    <td style="vertical-align: middle;">
                                        <a type="button" class="btn btn-success btn-sm"
                                            href="{{ url('/increment', $carts->product_id) }}">+</a>
                                        &nbsp;&nbsp;{{ $carts->quantity }}&nbsp;&nbsp;
                                        <a type="button" class="btn btn-danger btn-sm"
                                            href="{{ url('/decrement', $carts->product_id) }}">-</a>
                                    </td>
                                    <td style="vertical-align: middle;">₱ {{ $carts->price }}</td>
                                    @method('DELETE')
                                    @csrf
                                    <td style="vertical-align: middle;"><a class="btn btn-danger"
                                            href="{{ url('delete', $carts->product_id) }}" method="POST">X</a></td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="d-flex justify-content-end">
                            <h3>Total: ₱{{ $totalprice }} <button type="button" class="btn btn-dark"
                                    style="margin-bottom: 5px; margin-right: 10px; margin-left: 10px;"
                                    data-bs-toggle="modal" data-bs-target="#exampleModal">Check Out</button></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Order Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/checkout/{{ $carts->user_id }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <label>Shipping Address:
                                    @error('shipping_address')
                                        <small><i>*{{ $message }}</i></small>
                                    @enderror
                                </label>
                                </br>
                                <input type="text" name="shipping_address" id="shipping_address" class="form-control"
                                    required></br>

                                <div class="row">
                                    <div class="col">
                                        <label style="width:320px">Select Type of Shipment:
                                            @error('shipment_id')
                                                <small><i>*{{ $message }}</i></small>
                                            @enderror
                                        </label>
                                    </div>

                                    <div class="col">
                                        <label>Shipment Cost: </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <select id="shipment" class="form-select form-control" name="shipment_id"
                                            style="width:320px"required>
                                            <option value="">Select...</option>
                                            @foreach ($shipments as $shipment)
                                                <option value={{ $shipment->id }}>{{ $shipment->shipment_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col">
                                        <input type="text" name="shipment_cost" id="shipment_cost" class="form-control"
                                            disabled>
                                    </div>
                                </div></br>

                                <label>Select Type of Payment:
                                    @error('payment_id')
                                        <small><i>*{{ $message }}</i></small>
                                    @enderror
                                </label>
                                </br>
                                <select id="mySelect" class="form-select form-control" name="payment_id" required>
                                    <option value="">Select...</option>
                                    @foreach ($payments as $payment)
                                        <option value={{ $payment->id }}>{{ $payment->payment_name }}</option>
                                    @endforeach
                                </select></br>

                                <div id="otherOption" style="display:none;">
                                    <label>Credit Card Information:
                                        @error('credit_card')
                                            <small><i>*{{ $message }}</i></small>
                                        @enderror
                                    </label></br>
                                    <input type="text" name="credit_card" id="otherInput" class="form-control"
                                        placeholder="####-####-####-####"></br>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <input type="submit" class="btn btn-primary" value="Confirm Order">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <h1 style="text-align: center;  padding:20%;">No Products in Cart</h1>
        @endif
    </div>

    <script>
        document.getElementById("shipment").addEventListener("change", function() {
            if (this.value === "1") {
                document.getElementById("shipment_cost").value = "₱35.25";
            } else if (this.value === "2") {
                document.getElementById("shipment_cost").value = "₱45.67";
            } else if (this.value === "3") {
                document.getElementById("shipment_cost").value = "₱55.89";
            } else {
                document.getElementById("shipment_cost").value = "";
            }
        });

        document.getElementById("mySelect").addEventListener("change", function() {
            if (this.value === "2" || this.value === "3") {
                document.getElementById("otherOption").style.display = "block";
                document.getElementById("otherInput").required = true;

            } else {
                document.getElementById("otherOption").style.display = "none";
                document.getElementById("otherInput").required = false;
                document.getElementById("otherInput").name = null;
                document.getElementById("otherInput").value = "x";
            }
        });
    </script>
@endsection
