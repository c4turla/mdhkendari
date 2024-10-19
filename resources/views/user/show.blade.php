@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Details</h1>
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Full Name</th>
            <td>{{ $user->full_name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>User Level</th>
            <td>{{ $user->user_level }}</td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td>{{ $user->phone_number }}</td>
        </tr>
        <tr>
            <th>Address</th>
            <td>{{ $user->address }}</td>
        </tr>
        <tr>
            <th>Photo</th>
            <td>
                @if($user->photo)
                    <img src="{{ asset('storage/'.$user->photo) }}" alt="User Photo" style="width:100px;height:100px;">
                @else
                    No Photo
                @endif
            </td>
        </tr>
    </table>
    <a href="{{ route('user.index') }}" class="btn btn-primary">Back</a>
</div>
@endsection
