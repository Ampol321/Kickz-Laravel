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
            <h1><b>Brands</b></h1>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                Stored Images</button>
            <a href="{{ url('/brand/create') }}" class="btn btn-success btn-sm"> Add Brand </a>
        </center></br>
        <div class="container" style="width: 700px; border:2px solid #cecece;">
            {{ $dataTable->table() }}
            {{ $dataTable->scripts() }}
            {{-- <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td><b>Image:</b></td>
                        <td><b>Brand Name:</b></td>
                        <td><b>Actions:</b></td>
                    </tr>
                    @foreach ($brands as $brand)
                    <tr>
                        <td style="vertical-align: middle;"><img src="{{url($brand->img_path)}}" alt="" width="100px" height="100px"></td>
                        <td style="vertical-align: middle;">{{ $brand->brand_name }}</td>
                        <td style="vertical-align: middle;">
                            <a href="{{route('brand.edit',$brand->id)}}" title="Edit Brand"><button class="btn btn-primary">Edit</button></a>
                            <form method="POST" action="{{route('brand.destroy',$brand->id)}}" accept-charset="UTF-8" style="display:inline">
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
                    <h5 class="modal-title" id="exampleModalLongTitle">Brand Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        use App\Models\Brand;
                        $brands = Brand::all();
                    @endphp

                    @foreach ($brands as $brand)
                        @php
                            $images = explode(',', $brand->img_path);
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
