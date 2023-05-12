@extends('layouts.app')
@section('content')
<center><h1><b>Edit Shipment</b></h1></center></br>
<div class = "container" style="width: 500px; border:2px solid #cecece;">
    <form action="{{ url('shipment/' . $shipments->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PATCH")</br>
        <label>Image</label></br>
        <input type="file" name="shipment_img[]" multiple id="shipment_img" class="form-control" value="{{$shipments->shipment_img}}">
        @error('shipment_img')
            <small><i>*{{ $message }}</i></small>
        @enderror</br>

        <label>Shipment Name</label></br>
        <input type="text" name="shipment_name" id="shipment_name" class="form-control" value="{{$shipments->shipment_name}}">
        @error('shipment_name')
            <small><i>*{{ $message }}</i></small>
        @enderror</br>

        <label>Shipment Cost</label></br>
        <input type="text" name="shipment_cost" id="shipment_cost" class="form-control" value="{{$shipments->shipment_cost}}">
        @error('shipment_cost')
            <small><i>*{{ $message }}</i></small>
        @enderror</br>

        <input type="submit" value="Update" class="btn btn-success"></br></br>
    </form> 
</div>
@endsection