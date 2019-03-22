@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('contact.search')}}" class="row justify-content-center" method="get">
            @csrf
            <input type="text" name="query" placeholder="Contacts first or last name">
            <input type="submit" aria-hidden="true">
        </form>
        <div class="row justify-content-center">

            <div class="card col-md-2 m-1">
                <div class="card-body">
                    <a href="{{route('contact.create')}}">CREATE NEW</a>
                </div>
            </div>
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
