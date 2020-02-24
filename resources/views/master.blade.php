<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <script src="js/app.js"></script>
    <title>@yield('title')</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col align-self-center py-2">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>