@extends('layouts.app')

@section('content')

    <div class="container ">
        <a class="btn btn-primary" href="{{route('home')}}">HOME</a>
        <br>
        <hr>
        <h2>Create a Contact:</h2>
        <br>
        <div class="text-danger">
            @if(isset($failed))
                Contact creation failed: <br>
                {{$message}}
                @foreach($errors as $key => $error)
                    @foreach($error as $err)
                        {{$err}}
                        <br>
                        <br>
                    @endforeach
                @endforeach
            @endif
        </div>
        <form class="justify-content-center" action="{{route('contact.store')}}" method="post"
              enctype="multipart/form-data">
            @csrf
            Profile photo:
            <br>
            <input class="btn btn-outline-dark" type="file" name="profile_photo">
            <br>
            <br>
            <label>
                <input type="text" name="first_name">
            </label> First Name
            <br>
            <label>
                <input type="text" name="last_name">
            </label> Last Name
            <br>
            <label>
                <input type="text" name="email">
            </label> Email
            <br>
            <label >
                <input type="checkbox" name="favourite" value="1">Favourite
            </label>
            <br>
            <br>
            <br>
            <strong>Add a phone number</strong>
            <br>
            <i>Optional</i>
            <br>
            <label>
                <input type="text" name="phone_numbers[0][number]">
            </label> Number
            <br>
            <label>
                <input type="text" name="phone_numbers[0][name]">
            </label> Name
            <br>
            <label>
                <input type="text" name="phone_numbers[0][label]">
            </label> Label
            <br>
            <label>
                <input hidden type="text" name="phone_numbers[0][contact_id]">
            </label>
            <br>
            <br>

            <label>
                <input type="text" name="phone_numbers[1][number]">
            </label> Number
            <br>
            <label>
                <input type="text" name="phone_numbers[1][name]">
            </label> Name
            <br>
            <label>
                <input type="text" name="phone_numbers[1][label]">
            </label> Label
            <br>
            <label>
                <input hidden type="text" name="phone_numbers[1][contact_id]">
            </label>
            <br>
            <input class="btn btn-secondary" type="submit">
        </form>
    </div>
@endsection
