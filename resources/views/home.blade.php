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
                    <div class="card-header">CREATE NEW</div>

                    <div class="card-body">
                        <a href="{{route('contact.create')}}">CREATE</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Contacts</div>

                    <div class="card-body">
                        @foreach($contacts as $contact)
                            <form action="{{route('contact.update', $contact['id'])}}" method="post">
                                <input type="checkbox" name="favourite" value="{{$contact['favourite']}}" @if($contact['favourite']) checked @endif> Favourite <br>
                            </form>
                            <a href="{{route('contact.show', $contact['id'])}}">
                                <div>
                                    @if(!empty($contact['profile_photo']))
                                        <img src="{{asset('storage/' . $contact['profile_photo'])}}" width="500px"><br>
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
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
