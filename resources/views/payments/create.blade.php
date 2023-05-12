@extends('layouts.app')
@section('content')
<center><h1><b>Create Payment</b></h1></center></br>
<div class = "container" style="width: 500px; border:2px solid #cecece;">
    <form action="{{ url('payment') }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}</br>

        <label>Image</label></br>
        <input type="file" name="payment_img[]" multiple id="payment_img" class="form-control" placeholder="">
        @error('payment_img')
            <small><i>*{{ $message }}</i></small>
        @enderror</br>

        <label>Payment Name</label></br>
        <input type="text" name="payment_name" id="payment_name" class="form-control" placeholder="">
        @error('payment_name')
            <small><i>*{{ $message }}</i></small>
        @enderror</br>

        <input type="submit" value="Save" class="btn btn-success"></br></br>
    </form> 
</div>
@endsection