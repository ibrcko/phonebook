<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <a href="{{route('home')}}"> BACK </a>
        <br>
        <hr>
        <br>
        <strong>Contact</strong>
        <br>
        @if(!empty($contact['profile_photo']))
            <img src="{{asset('storage/' . $contact['profile_photo'])}}" width="500px"><br>
        @endif
        <br>
        NAME: {{$contact['first_name']}} {{$contact['last_name']}}
        <br>
        EMAIL: {{$contact['email']}}
        <br>
        IS FAVOURITE: {{$contact['favourite'] ? 'YES' : 'NO'}}
        <br>
        <a href="{{route('contact.edit', $contact['id'])}}"> Edit Contact</a> |
        <a href="{{route('contacts.destroy', $contact['id'])}}"> Delete Contact</a>
        <br><br>

        <strong>Phone Numbers</strong><br>
        @foreach($contact['phone_numbers'] as $number)
            NAME: {{$number['name']}}<br>
            NUMBER: {{$number['number']}}<br>
            LABEL: {{$number['label']}}<br>
            <a href="{{route('contact.phone-number.delete', $number['id'])}}"> Delete Number</a> <br><br>
        @endforeach
        <a href="{{route('contact.phone-number.create', $contact['id'])}}"> Add Another</a> <br><br>
    </body>
</html>
