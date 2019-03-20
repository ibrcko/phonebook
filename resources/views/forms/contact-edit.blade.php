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
<a href="{{route('contact.show', $contact)}}"> BACK </a>
<br>
@if(isset($error))

    Contact Update Failed!<br>
@elseif(isset($success))

    Contact Updated. <br>

@endif
<form action="{{route('contact.update', $contact['id'])}}" method="post" enctype="multipart/form-data">
    @csrf
    Edit Contact: <br><br>
    @if(!empty($contact['profile_photo']))
        <img src="{{asset('storage/' . $contact['profile_photo'])}}" width="500px"><br>
    @endif
    Profile photo: <input type="file" name="profile_photo"> <br><br>
    First Name <input type="text" name="first_name" value="{{$contact['first_name']}}"><br>
    Last Name <input type="text" name="last_name" value="{{$contact['last_name']}}"> <br>
    Email <input type="text" name="email" value="{{$contact['email']}}"> <br>
    <br><br>
    <input type="submit">
</form>

</body>
</html>
