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
            <h1><b>Shipments</b></h1>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                Stored Images</button>
            <a href="{{ url('/shipment/create') }}" class="btn btn-success btn-sm"> Add Shipment </a>
        </center></br>

        <div class="container">
            <div class="main-body">
                <div class="row gutters-sm">
                    <div class="col-md-4 mb-3">
                        @if (empty($shipmentChart))
                            <div></div>
                        @else
                            <div style="padding:50px">
                                {!! $shipmentChart->container() !!}
                                {!! $shipmentChart->script() !!}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-8" style="padding:5px; border:2px solid #cecece;">
                        {{ $dataTable->table() }}
                        {{ $dataTable->scripts() }}
                        {{-- <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td><b>Image:</b></td>
                        <td><b>Shipment Name:</b></td>
                        <td><b>Shipment Cost:</b></td>
                        <td><b>Actions:</b></td>
                    </tr>
                    @foreach ($shipments as $shipment)
                    <tr>
                        <td style="vertical-align: middle;"><img src="{{url($shipment->shipment_img)}}" width="100px" height="100px"></td>
                        <td style="vertical-align: middle;">{{$shipment->shipment_name}}</td>
                        <td style="vertical-align: middle;">₱{{$shipment->shipment_cost}}</td>
                        <td style="vertical-align: middle;">
                            <a href="{{route('shipment.edit',$shipment->id)}}"><button class="btn btn-primary">Edit</button></a>

                            <form method="POST" action="{{route('shipment.destroy',$shipment->id)}}" accept-charset="UTF-8" style="display:inline">
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
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Shipment Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        use App\Models\Shipment;
                        $shipments = Shipment::all();
                    @endphp

                    @foreach ($shipments as $shipment)
                        @php
                            $images = explode(',', $shipment->shipment_img);
                        @endphp
                        @foreach ($images as $item)
                            <img src="{{ $item }}" style="height:100px; width:100px">
                        @endforeach
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
