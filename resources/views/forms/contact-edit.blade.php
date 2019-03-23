@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{route('home')}}">HOME</a>
        <br>
        <hr>
        @if(isset($error))

            Contact Update Failed!<br>
        @elseif(isset($success))

            Contact Updated. <br>

        @endif
        <form action="{{route('contact.update', $contact['id'])}}" method="post" enctype="multipart/form-data">
            @csrf
            <h2>Edit Contact: </h2>
            <br>

            Profile photo:
            <br>
            @if(!empty($contact['profile_photo']))
                <img src="{{asset('storage/' . $contact['profile_photo'])}}" width="500px" alt="">
                <br>
            @endif
            <label>
                Upload new image:
                <br>
                <input type="file" name="profile_photo">
            </label>

            <br>
            <br>
            <label>
                <input type="text" name="first_name" value="{{$contact['first_name']}}">
            </label> First Name
            <br>
            <label>
                <input type="text" name="last_name" value="{{$contact['last_name']}}">
            </label> Last Name
            <br>
            <label>
                <input type="text" name="email" value="">
            </label> Email (leave it empty, if you want it not to change)
            <br>
            <br>
            <input type="submit">
        </form>
    </div>
@endsection
