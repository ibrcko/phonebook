@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{route('home')}}">HOME</a>
        <br>
        <hr>
        @if(session()->get('error'))
            <div class="text-danger">
            {{session()->get('message')}}
            @foreach(session()->get('error') as $key => $error)
                @foreach($error as $err)
                    {{$err}}
                    <br>
                    <br>
                @endforeach
            @endforeach
            </div>
        @elseif(session()->get('updated'))
            <div class="text-success">
                {{session()->get('message')}}
            </div>
        @endif
        <a href="{{route('contact.show', $contact)}}">Return to the Contact</a>
        <br>
        <form action="{{route('contact.update', $contact['id'])}}" method="post" enctype="multipart/form-data">
            @csrf
            <h2>Edit Contact: </h2>
            <br>

            Profile photo:
            <br>
            @if(!empty($contact['profile_photo']))
                <img src="{{asset('storage/' . $contact['profile_photo'])}}" width="300px" alt="">
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
                <input type="text" name="email">
            </label> Email (leave it empty, if you want it not to change)
            <br>
            <br>
            <input type="submit">
        </form>
    </div>
@endsection
