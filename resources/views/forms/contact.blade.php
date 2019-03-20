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
    <form action="{{route('contact.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        Create a Contact: <br><br>
        Profile photo: <input type="file" name="profile_photo"> <br><br>
        <input type="text" name="first_name"> Name <br>
        <input type="text" name="last_name"> Last Name <br>
        <input type="text" name="email"> Email <br>
        <input type="checkbox" name="favourite" value="1"> Favourite <br>
        <br><br>
        Add a phone number <br>
        <input type="text" name="phone_numbers[0][number]"> Number <br>
        <input type="text" name="phone_numbers[0][name]"> Name <br>
        <input type="text" name="phone_numbers[0][label]"> Label <br>
        <input hidden type="text" name="phone_numbers[0][contact_id]">
        <br><br>

        <input type="text" name="phone_numbers[1][number]"> Number <br>
        <input type="text" name="phone_numbers[1][name]"> Name <br>
        <input type="text" name="phone_numbers[1][label]"> Label <br>
        <input hidden type="text" name="phone_numbers[1][contact_id]">

        <input type="submit">
    </form>
</body>
</html>
