@extends('layouts.app')
@section('content')
    <center>
        <h1><b>Edit Order</b></h1>
    </center></br>
    
    <div class="container" style="width: 500px; border:2px solid #cecece;">
        <form action="/edit-order/{{ $processing->id }}" method="POST">
            @csrf
            @method('PUT')
            <label>Shipping Address:
                @error('shipping_address')
                    <small><i>*{{ $message }}</i></small>
                @enderror
            </label>
            </br>
            <input type="text" value="{{ $processing->shipping_address }}" name="shipping_address" id="shipping_address" class="form-control" required></br>

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
                    <select id="shipment" class="form-select form-control" name="shipment_id" style="width:320px"required>
                        <option value="{{ $processing->shipment_id }}">{{ $processing->shipment_name }}</option>
                        @foreach ($shipments as $shipment)
                            <option value={{ $shipment->id }}>{{ $shipment->shipment_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col">
                    <input type="text" name="shipment_cost" id="shipment_cost" class="form-control" disabled>
                </div>
            </div></br>

            <label>Select Type of Payment:
                @error('payment_id')
                    <small><i>*{{ $message }}</i></small>
                @enderror
            </label>
            </br>
            <select id="mySelect" class="form-select form-control" name="payment_id" required>
                <option value="{{ $processing->payment_id }}">{{ $processing->payment_name }}</option>
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
                <input type="text" name="credit_card" value="{{ $processing->credit_card }}" id="otherInput" class="form-control"
                    placeholder="####-####-####-####"></br>
            </div>

            <input type="submit" class="btn btn-primary" value="Confirm Edit">
        </form><br>
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
            }
        });
    </script>
@endsection
