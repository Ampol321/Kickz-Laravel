@extends('layouts.app')
@section('content')
    <center>
        <h1><b>Feedback Form</b></h1>
    </center></br>
    <div class="container" style="width: 500px; border:2px solid #cecece;">
        <form action="{{ url('rateorder/' . $delivered->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label>Ratings:</label></br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ratings" id="inlineRadio1" value="1">
                <label class="form-check-label" for="inlineRadio1">Bad</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ratings" id="inlineRadio2" value="2">
                <label class="form-check-label" for="inlineRadio2">Fair</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ratings" id="inlineRadio3" value="3">
                <label class="form-check-label" for="inlineRadio3">Okay</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ratings" id="inlineRadio4" value="4">
                <label class="form-check-label" for="inlineRadio4">Good</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ratings" id="inlineRadio5" value="5">
                <label class="form-check-label" for="inlineRadio5">Satisfied</label>
            </div></br></br>

            <label>Comment:</label></br>
            <textarea class="form-control" name="comments" id="message-text"></textarea></br>

            <input type="submit" class="btn btn-primary btn-block" value="Submit">
        </form><br>
    </div>
@endsection
