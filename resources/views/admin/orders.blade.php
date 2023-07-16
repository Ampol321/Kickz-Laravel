@extends('layouts.tables')
@extends('layouts.app')
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" style="display:inline-block">x</button>
            {{ session()->get('message') }}
        </div>
    @endif
    <center>
        <h1><b>Update Orders</b></h1>
    </center></br>
    <div class="container" style="width: 1000px; padding:10px; border:2px solid #cecece;">
        {{ $dataTable->table() }}
        {{ $dataTable->scripts() }}
    </div>
@endsection
