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
    <form action="{{route('contact.create')}}" method="post">
        @csrf
        <input type="text" name="first_name"> Name
        <input type="text" name="last_name"> Last Name
        <input type="text" name="email"> Email
        <input type="checkbox" name="favourite" value="1"> Favourite
        <input type="submit">
    </form>
</body>
</html>
