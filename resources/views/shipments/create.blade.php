@extends('layouts.app')
@section('content')
<center><h1><b>Create Shipment</b></h1></center></br>
<div class = "container" style="width: 500px; border:2px solid #cecece;">
    <form action="{{ url('shipment') }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}</br>

        <label>Image</label></br>
        <input type="file" name="shipment_img[]" multiple id="shipment_img" class="form-control" placeholder="">
        @error('shipment_img')
            <small><i>*{{ $message }}</i></small>
        @enderror</br>

        <label>Shipment Name</label></br>
        <input type="text" name="shipment_name" id="shipment_name" class="form-control" placeholder="">
        @error('shipment_name')
            <small><i>*{{ $message }}</i></small>
        @enderror</br>

        <label>Shipment Cost</label></br>
        <input type="text" name="shipment_cost" id="shipment_cost" class="form-control">
        @error('shipment_cost')
            <small><i>*{{ $message }}</i></small>
        @enderror</br>

        <input type="submit" value="Save" class="btn btn-success"></br></br>
    </form> 
</div>
@endsection