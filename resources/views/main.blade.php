<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otzyvy.fun</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">

    <link type="text/css" href="{{asset('styles/styles.css')}}?<?php echo time(); ?>" rel="stylesheet" />
</head>

<body>
    <header>
        <a>OTZYVY.FUN</a>
    </header>

    @yield('content')

</body>
<script src="{{asset('scripts/loader.js')}}"></script>

</html>