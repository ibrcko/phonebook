@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        You are logged in!
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">create new</div>

                    <div class="card-body">
                        <a href="{{route('contact.create')}}">CREATE</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Contacts</div>

                    <div class="card-body">
                        @foreach($contacts as $contact)
                            {{$contact['first_name']}}
                            {{$contact['last_name']}}
                            {{$contact['email']}}
                            <img src="{{asset($contact['profile_photo'])}}" alt="img">
                            {{$contact['favourite']}}
                            <a href="{{route('contact.delete', $contact['id'])}}">DELETE</a>
                            <br>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
