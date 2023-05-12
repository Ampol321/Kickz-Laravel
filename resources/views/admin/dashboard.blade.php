@extends('layouts.tables')
@extends('layouts.app')
@section('content')
    <div class="card-body">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-md-9 col-lg-7 col-xl-5">
                    <div class="card" style="border-radius: 15px;">
                        <div class="card-body p-4">
                            <div class="d-flex text-black">
                                <div class="flex-shrink-0">
                                    @if (empty(Auth::user()->user_img))
                                        <img src="http://127.0.0.1:8000/storage/images/profile.jpg"
                                            alt="Generic placeholder image" class="img-fluid"
                                            style="width: 180px; border-radius: 10px;">
                                    @else
                                        <img src="{{ url(Auth::user()->user_img) }}" alt="Generic placeholder image"
                                            class="img-fluid" style="width: 180px; border-radius: 10px;">
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                                    <p class="mb-2 pb-1" style="color: #2b2a2a;">{{ Auth::user()->email }}</p>
                                    <div class="d-flex justify-content-start rounded-3 p-2 mb-2"
                                        style="background-color: #efefef;">
                                        <marquee>
                                            <h1>Administrator</h1>
                                        </marquee>
                                    </div>
                                    <div class="d-flex pt-1">
                                        <a href="{{ url('/edit-profile', Auth::user()->id) }}" type="button"
                                            class="btn btn-outline-primary me-1 flex-grow-1">Edit Profile</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div></br>

        @if ($orders->count())
            <div class="col-md-12 text-center">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    @if ($orders->count())
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card1"
                            aria-expanded="false" aria-controls="card1">All <span
                                class="badge badge-dark">{{ $orders->count() }}</span></button>
                    @else
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card1"
                            aria-expanded="false" aria-controls="card1">All </button>
                    @endif

                    @if ($processorders->count())
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card2"
                            aria-expanded="false" aria-controls="card1">Processing <span
                                class="badge badge-dark">{{ $processorders->count() }}</span></button>
                    @else
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card2"
                            aria-expanded="false" aria-controls="card1">Processing</button>
                    @endif

                    @if ($ondeliveryorders->count())
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card3"
                            aria-expanded="false" aria-controls="card3">On Delivery <span
                                class="badge badge-dark">{{ $ondeliveryorders->count() }}</span></button>
                    @else
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card3"
                            aria-expanded="false" aria-controls="card3">On Delivery </button>
                    @endif

                    @if ($deliveredorders->count())
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card4"
                            aria-expanded="false" aria-controls="card4">Delivered <span
                                class="badge badge-dark">{{ $deliveredorders->count() }}</span></button>
                    @else
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card4"
                            aria-expanded="false" aria-controls="card4">Delivered </button>
                    @endif

                    @if ($cancelledorders->count())
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card5"
                            aria-expanded="false" aria-controls="card5">Cancelled <span
                                class="badge badge-dark">{{ $cancelledorders->count() }}</span></button>
                    @else
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#card5"
                            aria-expanded="false" aria-controls="card5">Cancelled </button>
                    @endif
                </div>
            </div></br>

            <div class="row">
                <div class="collapse" id="card1">
                    @foreach ($orders as $order)
                        <div class="card" style="width: 800px; border:2px solid #cecece; margin:auto;">
                            <h5 class="card-header custom-header"><b>Order no: #{{ $order->id }}</b> <i
                                    style="float: right;">*{{ $order->status }}</i></h5>

                            @foreach ($items->where('order_id', $order->id) as $item)
                                <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                                    <div class="media-body order-2 order-lg-1">
                                        <h5 class="mt-0 font-weight-bold mb-2">{{ $item->product_name }}</h5>
                                        <p class="font-italic text-muted mb-0 small">Quantity: x{{ $item->quantity }}</p>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <p class="font-italic text-muted mb-0 small">Price: ₱{{ $item->price }}</p>
                                        </div>
                                    </div>
                                    <img src="{{ url($item->product_img) }}" width="100px" height="100px"
                                        class="ml-lg-5 order-1 order-lg-2">
                                </div>
                            @endforeach

                            <div class="card-footer">
                                <div class="text-right">
                                    <p>Date Ordered: {{ $order->date_ordered }}
                                        <br>Shipping Address: {{ $order->shipping_address }}
                                        <br>Total Price: ₱{{ $totalprice->where('id', $order->id)->first()->totalprice }}
                                        <br>Shipment Cost: ₱{{ $order->shipment_cost }}
                                    </p>
                                    <h5 class="card-text"><b>Order Total:</b>
                                        ₱{{ $totalprice->where('id', $order->id)->first()->totalprice + $order->shipment_cost }}
                                    </h5>
                                </div>
                            </div>
                        </div></br>
                    @endforeach
                </div>

                @if ($processorders->count())
                    <div class="collapse" id="card2">
                        @foreach ($processorders as $process)
                            <div class="card" style="width: 800px; border:2px solid #cecece; margin:auto;">
                                <h5 class="card-header custom-header"><b>Order no: #{{ $process->id }}</b> <i
                                        style="float: right;">*{{ $process->status }}</i></h5>

                                @foreach ($items->where('order_id', $process->id) as $item)
                                    <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                                        <div class="media-body order-2 order-lg-1">
                                            <h5 class="mt-0 font-weight-bold mb-2">{{ $item->product_name }}</h5>
                                            <p class="font-italic text-muted mb-0 small">Quantity: x{{ $item->quantity }}
                                            </p>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <p class="font-italic text-muted mb-0 small">Price: ₱{{ $item->price }}
                                                </p>
                                            </div>
                                        </div>
                                        <img src="{{ url($item->product_img) }}" width="100px" height="100px"
                                            class="ml-lg-5 order-1 order-lg-2">
                                    </div>
                                @endforeach

                                <div class="card-footer">
                                    <div class="text-right">
                                        <a href="{{ url('edit-form', $process->id)}}" style="float: left; margin-right: 5px"
                                            class="btn btn-outline-success">Edit Order</a>
                                        <button style="float: left" class="btn btn-outline-danger" data-toggle="modal"
                                            data-target="#exampleModalCenter">Cancel Order</button>
                                        <p> Date Ordered: {{ $process->date_ordered }}
                                            <br>Shipping Address: {{ $process->shipping_address }}
                                            <br>Total Price:
                                            ₱{{ $totalprice->where('id', $process->id)->first()->totalprice }}
                                            <br>Shipment Cost: ₱{{ $process->shipment_cost }}
                                        </p>
                                        <h5 class="card-text"><b>Order Total:</b>
                                            ₱{{ $totalprice->where('id', $process->id)->first()->totalprice + $process->shipment_cost }}
                                        </h5>
                                    </div>
                                </div>
                            </div></br>
                        @endforeach
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Cancel Order</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to cancel this order?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a type="button" class="btn btn-danger"
                                        href="{{ url('/cancelorder', $process->id) }}">Cancel Order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="collapse" id="card2">
                        <div class="card" style="width: 800px; border:2px solid #cecece; margin:auto;">
                            <h1 style="text-align: center;  padding:15%;">No processing orders</h1>
                        </div>
                    </div>
                @endif

                @if ($ondeliveryorders->count())
                    <div class="collapse" id="card3">
                        @foreach ($ondeliveryorders as $delivery)
                            <div class="card" style="width: 800px; border:2px solid #cecece; margin:auto;">
                                <h5 class="card-header custom-header"><b>Order no: #{{ $delivery->id }}</b> <i
                                        style="float: right;">*{{ $delivery->status }}</i></h5>

                                @foreach ($items->where('order_id', $delivery->id) as $item)
                                    <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                                        <div class="media-body order-2 order-lg-1">
                                            <h5 class="mt-0 font-weight-bold mb-2">{{ $item->product_name }}</h5>
                                            <p class="font-italic text-muted mb-0 small">Quantity: x{{ $item->quantity }}
                                            </p>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <p class="font-italic text-muted mb-0 small">Price: ₱{{ $item->price }}
                                                </p>
                                            </div>
                                        </div>
                                        <img src="{{ url($item->product_img) }}" width="100px" height="100px"
                                            class="ml-lg-5 order-1 order-lg-2">
                                    </div>
                                @endforeach

                                <div class="card-footer">
                                    <div class="text-right">
                                        <a href="{{ url('/receipt', $delivery->id) }}"
                                            style="float: left; margin-right: 5px" class="btn btn-outline-success">Print
                                            Receipt</a>
                                        <p>Date Ordered: {{ $delivery->date_ordered }}
                                            <br>Shipping Address: {{ $delivery->shipping_address }}
                                            <br>Total Price:
                                            ₱{{ $totalprice->where('id', $delivery->id)->first()->totalprice }}
                                            <br>Shipment Cost: ₱{{ $delivery->shipment_cost }}
                                        </p>
                                        <h5 class="card-text"><b>Order Total:</b>
                                            ₱{{ $totalprice->where('id', $delivery->id)->first()->totalprice + $delivery->shipment_cost }}
                                        </h5>
                                    </div>
                                </div>
                            </div></br>
                        @endforeach
                    </div>
                @else
                    <div class="collapse" id="card3">
                        <div class="card" style="width: 800px; border:2px solid #cecece; margin:auto;">
                            <h1 style="text-align: center;  padding:15%;">No orders on delivery</h1>
                        </div>
                    </div>
                @endif

                @if ($deliveredorders->count())
                    <div class="collapse" id="card4">
                        @foreach ($deliveredorders as $delivered)
                            <div class="card" style="width: 800px; border:2px solid #cecece; margin:auto;">
                                <h5 class="card-header custom-header"><b>Order no: #{{ $delivered->id }}</b> <i
                                        style="float: right;">*{{ $delivered->status }}</i></h5>

                                @foreach ($items->where('order_id', $delivered->id) as $item)
                                    <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                                        <div class="media-body order-2 order-lg-1">
                                            <h5 class="mt-0 font-weight-bold mb-2">{{ $item->product_name }}</h5>
                                            <p class="font-italic text-muted mb-0 small">Quantity: x{{ $item->quantity }}
                                            </p>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <p class="font-italic text-muted mb-0 small">Price: ₱{{ $item->price }}
                                                </p>
                                            </div>
                                        </div>
                                        <img src="{{ url($item->product_img) }}" width="100px" height="100px"
                                            class="ml-lg-5 order-1 order-lg-2">
                                    </div>
                                @endforeach

                                <div class="card-footer">
                                    <div class="text-right">
                                        @if ($delivered->ratings !== null && $delivered->comments !== null)
                                            <button style="float: left; margin-right: 5px"
                                                class="btn btn-outline-secondary" disabled>Rate</button>
                                        @else
                                            <a href="{{ url('feedback-form', $delivered->id)}}" style="float: left; margin-right: 5px"
                                                class="btn btn-outline-success">Rate</a>
                                        @endif
                                        <p>Date Ordered: {{ $delivered->date_ordered }}
                                            <br>Shipping Address: {{ $delivered->shipping_address }}
                                            <br>Total Price:
                                            ₱{{ $totalprice->where('id', $delivered->id)->first()->totalprice }}
                                            <br>Shipment Cost: ₱{{ $delivered->shipment_cost }}
                                        </p>
                                        <h5 class="card-text"><b>Order Total:</b>
                                            ₱{{ $totalprice->where('id', $delivered->id)->first()->totalprice + $delivered->shipment_cost }}
                                        </h5>
                                    </div>
                                </div>
                            </div></br>
                        @endforeach
                    </div>
                @else
                    <div class="collapse" id="card4">
                        <div class="card" style="width: 800px; border:2px solid #cecece; margin:auto;">
                            <h1 style="text-align: center;  padding:15%;">No delivered orders</h1>
                        </div>
                    </div>
                @endif

                @if ($cancelledorders->count())
                    <div class="collapse" id="card5">
                        @foreach ($cancelledorders as $cancelled)
                            <div class="card" style="width: 800px; border:2px solid #cecece; margin:auto;">
                                <h5 class="card-header custom-header"><b>Order no: #{{ $cancelled->id }}</b> <i
                                        style="float: right;">*{{ $cancelled->status }}</i></h5>

                                @foreach ($items->where('order_id', $cancelled->id) as $item)
                                    <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                                        <div class="media-body order-2 order-lg-1">
                                            <h5 class="mt-0 font-weight-bold mb-2">{{ $item->product_name }}</h5>
                                            <p class="font-italic text-muted mb-0 small">Quantity: x{{ $item->quantity }}
                                            </p>
                                            <div class="d-flex align-items-center justify-content-between mt-1">
                                                <p class="font-italic text-muted mb-0 small">Price: ₱{{ $item->price }}
                                                </p>
                                            </div>
                                        </div>
                                        <img src="{{ url($item->product_img) }}" width="100px" height="100px"
                                            class="ml-lg-5 order-1 order-lg-2">
                                    </div>
                                @endforeach

                                <div class="card-footer">
                                    <div class="text-right">
                                        <p>Date Ordered: {{ $cancelled->date_ordered }}
                                            <br>Shipping Address: {{ $cancelled->shipping_address }}
                                            <br>Total Price:
                                            ₱{{ $totalprice->where('id', $cancelled->id)->first()->totalprice }}
                                            <br>Shipment Cost: ₱{{ $cancelled->shipment_cost }}
                                        </p>
                                        <h5 class="card-text"><b>Order Total:</b>
                                            ₱{{ $totalprice->where('id', $cancelled->id)->first()->totalprice + $cancelled->shipment_cost }}
                                        </h5>
                                    </div>
                                </div>
                            </div></br>
                        @endforeach
                    </div>
                @else
                    <div class="collapse" id="card5">
                        <div class="card" style="width: 800px; border:2px solid #cecece; margin:auto;">
                            <h1 style="text-align: center;  padding:15%;">No cancelled orders</h1>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <h1 style="text-align: center;  padding:12%;">No orders yet</h1>
        @endif
    </div>
    </div>
    
    <script>
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
