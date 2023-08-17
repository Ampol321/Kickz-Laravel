@extends('layouts.tables')
@extends('layouts.app')
@section('content')
    {{-- JQuery DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    @if (session()->has('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
            {{ session()->get('message') }}
        </div>
    @endif

    <div class="card-body">
        @if ($cart->count())
            <div class="cart">
                <center>
                    <h1><b>Shopping Cart</b></h1>
                </center><br>
                <div class="cartContainer" style="margin: 0 auto; width: 1100px; padding:5px; border:2px solid #cecece;">
                    <table id="cartsTable" data-id="{{ Auth::user()->id }}" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table><br>
                    <div class="d-flex justify-content-end totalprice">
                        <h3>Total: ₱<span class="totalPrice">{{ $totalprice }}</span> <button type="button"
                                class="btn btn-dark" style="margin-bottom: 5px; margin-right: 10px; margin-left: 10px;"
                                data-bs-toggle="modal" data-bs-target="#exampleModal">Check Out</button></h3>
                    </div>
                </div>
            </div>

            <!-- Modal for transaction -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Order Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/checkout/{{ Auth::user()->id }}" method="POST">
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
            <h1 class="message" style="text-align: center;  padding:20%;">No Products in Cart</h1>
        @endif
    </div>

    <script src="{{ asset('js/shopping-cart.js') }}">
        var cartCount = {{ $cart->count() }};
        var total = {{ $totalprice }}
    </script>

    {{-- <div style="margin: 0 auto; width: 65%;">
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
                            <td style="vertical-align: middle;"><img src="{{ url($carts->product_img) }}" width="100px"
                                    height="100px"></td>
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
            </div>
        </div>
    </div> --}}
@endsection
