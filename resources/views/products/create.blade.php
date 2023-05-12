@extends('layouts.app')
@section('content')
    <center>
        <h1><b>Add Product</b></h1>
    </center></br>
    <div class="container" style="width: 700px; border:2px solid #cecece;">
        <form action="{{ url('product') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <label>Image</label></br>
            <input type="file" name="product_img[]" multiple id="product_img" class="form-control">
            @error('product_img')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label>Product Name</label></br>
            <input type="text" name="product_name" id="product_name" class="form-control">
            @error('product_name')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label for="brand__name">Brand Name</label>
            <select class="form-select form-control" name="brand_id">
                <option value=""selected>Select Brand</option>
                @foreach ($brands as $brand)
                    <option value={{ $brand->id }}>{{ $brand->brand_name }}</option>
                @endforeach
            </select>
            @error('brand_id')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label>Colorway</label></br>
            <input type="text" name="colorway" id="colorway" class="form-control">
            @error('colorway')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label for="type_name">Type</label>
            <select class="form-select form-control" name="type_id">
                <option value="" selected>Select Type</option>
                @foreach ($types as $type)
                    <option value={{ $type->id }}>{{ $type->type_name }}</option>
                @endforeach
            </select>
            @error('type_id')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label>Size</label></br>
            <input type="number" name="size" id="size" class="form-control">
            @error('size')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label>Price</label></br>
            <input type="text" name="price" id="price" class="form-control">
            @error('price')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <input type="submit" value="Save" class="btn btn-success"></br></br>
        </form>
    </div>
@endsection
