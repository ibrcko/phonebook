@extends('layouts.app')

@section('content')
    <div class="container">
        <a class="btn btn-primary" href="{{route('home')}}">HOME</a>
        <br>
        <hr>
        <div class="text-success">
            @if(isset($created))
                Contact created successfully! <br>
            @endif
            @if(isset($deletion))
                @if(!empty($message))
                    {{$message}}
                @endif
            @endif
        </div>
        <strong>Contact</strong>
        <br>
        Profile photo:
        <br>
        @if(!empty($contact['profile_photo']))
            <img src="{{asset('storage/' . $contact['profile_photo'])}}" width="300px"><br>
        @else
            <i>no photo</i>
            <br>
        @endif
        <br>
        NAME: {{$contact['first_name']}} {{$contact['last_name']}}
        <br>
        EMAIL: {{$contact['email']}}
        <br>
        IS FAVOURITE: {{$contact['favourite'] ? 'YES' : 'NO'}}
        <br>
        <br>
        <a class="btn btn-outline-primary" href="{{route('contact.edit', $contact['id'])}}"> Edit </a>
        <a class="btn btn-outline-primary" href="{{route('contact.delete', $contact['id'])}}"> Delete </a>
        <br>
        <hr>
        <strong>Phone Numbers</strong><br>
        <div class="text-danger">
            @if(isset($failedPn) && $failedPn)
                Phone number creation failed: <br>
                {{$message}}
                @foreach($errors as $key => $error)
                    @foreach($error as $err)
                        {{$err}}
                        <br>
                        <br>
                    @endforeach
                @endforeach
            @elseif(isset($createdPN))
                <div class="text-success">
                    Phone number created successfully!
                </div>
            @elseif(session()->get('deletion'))
                <div class="text-black-50">
                    Phone number deleted successfully!
                </div>
            @endif
        </div>
        @foreach($contact['phone_numbers'] as $number)
            NAME: {{$number['name']}}<br>
            NUMBER: {{$number['number']}}<br>
            LABEL: {{$number['label']}}<br>
            <a href="{{route('contact.phone-number.delete', $number)}}"> Delete </a><br>
        @endforeach
        <br>
        <a class="btn btn-outline-primary" href="{{route('contact.phone-number.create', $contact['id'])}}"> Add </a>
        <br><br>
    </div>
@endsection
