@extends('layouts.app')
@section('content')
    <center>
        <h1><b>Edit Brand</b></h1>
    </center></br>
    <div class="container" style="width: 500px; border:2px solid #cecece;">
        <div class="card-body">
            <form action="{{ url('brand/' . $brands->id) }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                @method('PATCH')</br>
                <label>Image @error('img_path')
                  <small><i>*{{ $message }}</i></small>
                @enderror</label></label></br>
                <input type="file" name="img_path[]" multiple id="img_path" value="{{ $brands->img_path }}" class="form-control"></br>
                
                <label>Brand Name @error('brand_name')
                  <small><i>*{{ $message }}</i></small>
                @enderror</label></label></br>
                <input type="text" name="brand_name" id="brand_name" value="{{ $brands->brand_name }}"
                    class="form-control"></br>
                    
                <input type="submit" value="Update" class="btn btn-success"></br></br>
            </form>
        </div>
    </div>
@stop
