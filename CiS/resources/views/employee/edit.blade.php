@extends('layouts/conquer');

@section('content') 
<form method="POST" action="{{ route('employe.update', $data->id) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="name">Employee Name</label>
        <input type="text" class="form-control" name="name" aria-describedby="typeHelp" placeholder="Enter customer's name" value="{{ $data -> name }}">

        <label for="address">Employee Phone Number</label>
        <input type="text" class="form-control" name="phone_number" aria-describedby="typeHelp" placeholder="Enter customer's phone number" value="{{ $data -> phone_number }}">

        <label for="address">Employee Address</label>
        <input type="text" class="form-control" name="address" aria-describedby="typeHelp" placeholder="Enter customer's address" value="{{ $data -> address }}">

    </div>
    <a class ="btn btn-info" href="{{ url()->previous() }}"> Cancel </a>
    <button type="submit" class="btn btn-primary">Submit</button>

    
</form>
@endsection