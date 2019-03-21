@extends('layouts.app')

@section('content')

    <form action="" class="row justify-content-center">
        <input  type="text">
    </form>
    <div class="container">
        <div class="row justify-content-center">

            @foreach($contacts as $contact)
                <div class="card col-md-2 m-1">
                    <div class="card-body">

                        @php
                            $favourite = $contact['favourite'] ? 0 : 1;
                            if(!$favourite)
                                $buttonValue = 'Remove';
                            else
                                $buttonValue = 'Add';
                        @endphp
                        <form action="{{route('contact.update', $contact['id'])}}" method="post">
                            <input type="text"
                                   name="favourite"
                                   value="{{$favourite}}"
                                   hidden>
                            @csrf
                            <input type="submit" value="{{$buttonValue}}">
                            <br>
                        </form>

                        <a href="{{route('contact.show', $contact['id'])}}">
                            <div>
                                @if(!empty($contact['profile_photo']))
                                    <img src="{{asset('storage/' . $contact['profile_photo'])}}" width="100px"><br>
                                @endif
                                Name: {{$contact['first_name']}} {{$contact['last_name']}}
                                <br>
                                Email: {{$contact['email']}}
                                <br>
                                <a href="{{route('contact.delete', $contact['id'])}}">DELETE</a>
                                <br>
                                <br>
                            </div>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection
