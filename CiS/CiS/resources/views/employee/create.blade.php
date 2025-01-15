@extends('layouts.conquer')

@section('content') 
<form method="POST" action="{{ route('employe.store') }}">
    @csrf
    <div class="form-group">
        <label for="cust_name">Employee Name</label>
        <input type="text" class="form-control" name="emp_name" aria-describedby="nameHelp" placeholder="Enter Your Name">
        <small id="nameHelp" class="form-text text-muted">Please enter your name</small>
    </div>

    <div class="form-group">
        <label for="user_id_cust">Phone Number</label>
        <input type="text" class="form-control" name="emp_phone" aria-describedby="phoneNumberHelp" placeholder="Enter Phone Number">
        <small id="userIdHelp" class="form-text text-muted">Please enter the Phone Number</small>
    </div>

    <div class="form-group">
        <label for="cust_address">Address</label>
        <input type="text" class="form-control" name="emp_address" aria-describedby="addressHelp" placeholder="Enter Your Address">
        <small id="addressHelp" class="form-text text-muted">Please enter your address</small>
    </div>

    <div class="form-group">
        <label for="cust_address">User ID</label>
        <input type="text" class="form-control" name="user_id_emp" aria-describedby="userID" placeholder="Enter Your User ID">
        <small id="addressHelp" class="form-text text-muted">Please enter your user ID</small>
    </div>
    

    <a class="btn btn-info" href="{{ url()->previous() }}">Cancel</a>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection
