@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('contact.phone-number.store', $contact)}}" method="post">
            @csrf
            <a href="{{route('home')}}">HOME</a>
            <br>
            <hr>
            <h2>Add a phone number:</h2>
            <br>
            <div class="text-danger">
                @if(isset($failed))
                    Phone number creation failed: <br>
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
            <label>
                <input type="text" name="phone_numbers[0][number]">
            </label> Number <br>
            <label>
                <input type="text" name="phone_numbers[0][name]">
            </label> Name <br>
            <label>
                <input type="text" name="phone_numbers[0][label]">
            </label> Label <br>
            <label>
                <input hidden type="text" name="phone_numbers[0][contact_id]">
            </label>
            <br><br>

            <input type="submit">
        </form>
    </div>
@endsection
