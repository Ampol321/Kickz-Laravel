@extends('layouts.app')
@section('content')
    <center>
        <h1><b>Edit Product</b></h1>
    </center></br>
    <div class="container" style="width: 700px; border:2px solid #cecece;">
        <form action="{{ url('product/' . $products->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <label>Image</label></br>
            <input type="file" name="product_img[]" multiple id="product_img" value="{{ $products->product_img }}"
                class="form-control">
            @error('product_img')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label>Product Name</label></br>
            <input type="text" name="product_name" id="product_name" value="{{ $products->product_name }}"
                class="form-control">
            @error('product_name')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label for="brand_name">Brand Name</label>
            <select class="form-select form-control" name="brand_id">
                <option selected value="{{ $products->brand_id }}">{{ $products->brand_name }}</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
            </select>
            @error('brand_id')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label>Colorway</label></br>
            <input type="text" name="colorway" id="colorway" value="{{ $products->colorway }}"
                class="form-control">
            @error('colorway')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label for="type_name">Type</label>
            <select class="form-select form-control" name="type_id">
                <option selected value="{{ $products->type_id }}">{{ $products->type_name }}</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                @endforeach
            </select>
            @error('type_id')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label>Size</label></br>
            <input type="number" name="size" id="size" value="{{ $products->size }}" class="form-control">
            @error('size')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label>Price</label></br>
            <input type="text" name="price" id="price" value="{{ $products->price }}" class="form-control">
            @error('price')
                <small><i>*{{ $message }}</i></small>
            @enderror</br>

            <label>Stock</label></br>
            <input type="number" name="stock" id="stock" value="{{ $stocks->stock }}" class="form-control"></br>
            
            <input type="submit" value="Update" class="btn btn-success"></br></br>
        </form>
@endsection
