@extends('layouts.app')

@section('content')

    <form action="{{route('contact.favourite.search')}}" class="row justify-content-center" method="get">
        @csrf
        <input type="text" name="query" placeholder="Contacts first or last name">
        <input class="btn btn-secondary m-1" type="submit" aria-hidden="true" value="Search">
    </form>
    <div class="container">
        <div class="row justify-content-center">

            @foreach($contacts as $contact)
                <div class="card col-md-2 m-3 p-0">
                    <div class="card-body">

                        @php
                            $favourite = $contact['favourite'] ? 0 : 1;
                            if (!$favourite) {
                                $buttonValue = 'Remove <3';
                                $buttonClass = 'btn btn-outline-danger';
                                }
                            else {
                                $buttonValue = 'Add <3';
                                $buttonClass = 'btn btn-outline-secondary';
                            }
                        @endphp
                        <form action="{{route('contact.update', $contact['id'])}}" method="post">
                            <input type="text"
                                   name="favourite"
                                   value="{{$favourite}}"
                                   hidden>
                            @csrf
                            <input class="{{$buttonClass}}" type="submit" value="{{$buttonValue}}">
                            <br>
                            <br>
                        </form>

                        <a href="{{route('contact.show', $contact['id'])}}">
                            <div>
                                @if(!empty($contact['profile_photo']))
                                    <img src="{{asset('storage/' . $contact['profile_photo'])}}" width="100px">
                                    <br>
                                    <br>
                                @endif
                                {{$contact['first_name']}} {{$contact['last_name']}}
                                <br>
                                <br>
                                <br>
                                <a class="btn btn-outline-primary" href="{{route('contact.delete', $contact['id'])}}">DELETE</a>
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
