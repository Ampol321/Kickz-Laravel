@extends('layouts.app')
@section('content')
<div class="card-body">
  <div class="container" style="width: 700px">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Profile</div>
                  <div class="card-body">
                    <form action="{{ url('profile/' .$users->id) }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      @method("PATCH")
                      
                      @if(empty(Auth::user()->user_img))
                        <center><img src="http://127.0.0.1:8000/storage/images/profile.jpg"
                        alt="Generic placeholder image" class="img-fluid"
                        style="width: 180px; border-radius: 10px;"></br></center>
                      @else
                        <center><img src= "{{url(Auth::user()->user_img)}}" alt="Generic placeholder image" class="img-fluid"
                          style="width: 180px; border-radius: 10px;"></br></center>
                      @endif

                      <label>Cover Photo</label></br>
                      <input type="file" name="user_img" id="user_img" value="{{$users->user_img}}" class="form-control">
                        @error('user_img')
                          <small><i>*{{ $message }}</i></small>
                        @enderror</br>

                      <label>Name</label></br>
                      <input type="text" name="name" id="name" value="{{$users->name}}" class="form-control">
                        @error('name')
                        <small><i>*{{ $message }}</i></small>
                        @enderror</br>
                      
                      <label>Email</label></br>
                      <input type="text" name="email" id="email" value="{{$users->email}}" class="form-control">
                        @error('email')
                        <small><i>*{{ $message }}</i></small>
                        @enderror</br>

                      <label>Phone</label></br>
                      <input type="text" name="phone" id="phone" value="{{$users->phone}}" class="form-control">
                        @error('phone')
                        <small><i>*{{ $message }}</i></small>
                        @enderror</br>

                      <input type="submit" value="Update" class="btn btn-success"></br>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </div>
  </div>
</div>
@stop