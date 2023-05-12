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
        <center>
            <h1><b>Payments</b></h1>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                Stored Images</button>
            <a href="{{ url('/payment/create') }}" class="btn btn-success btn-sm"> Add Payment </a>
        </center></br>
        <div class="container" style="width: 700px; border:2px solid #cecece;">
            {{ $dataTable->table() }}
            {{ $dataTable->scripts() }}
            {{-- <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td><b>Image:</b></td>
                        <td><b>Payment Name:</b></td>
                        <td><b>Actions:</b></td>
                    </tr>
                    @foreach ($payments as $payment)
                    <tr>
                        <td style="vertical-align: middle;"><img src="{{url($payment->payment_img)}}" width="100px" height="100px"></td>
                        <td style="vertical-align: middle;">{{$payment->payment_name}}</td>
                        <td style="vertical-align: middle;">
                            <a href="{{route('payment.edit',$payment->id)}}"><button class="btn btn-primary">Edit</button></a>
                            <form method="POST" action="{{route('payment.destroy',$payment->id)}}" accept-charset="UTF-8" style="display:inline">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger" onclick="return confirm(&quot;Confirm delete?&quot;)">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div> --}}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Payment Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        use App\Models\Payment;
                        $payments = payment::all();
                    @endphp

                    @foreach ($payments as $payment)
                        @php
                            $images = explode(',', $payment->payment_img);
                        @endphp
                        @foreach ($images as $item)
                            <img src="{{$item}}" style="height:100px; width:100px">
                        @endforeach
                    @endforeach
                    {{-- @php
                        $image = DB::table('payments')->where('id', 7)->first();
                        $images = explode(',',$image->payment_img);
                    @endphp
                    @foreach ($images as $item)
                        <img src = "{{URL::to($item)}}" style="height:100px; width:100px">
                    @endforeach

                    @foreach ($payments as $payment)
                        <div>
                            @php
                                $imagePaths = explode('|', $payment->payment_img);
                            @endphp
                            @foreach ($imagePaths as $path)
                                <img src="{{ asset($path) }}" alt="{{ $payment->payment_name }}" />
                            @endforeach
                        </div>
                    @endforeach --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
