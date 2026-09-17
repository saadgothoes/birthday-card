<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Make a birthday they actually remember — a PIN-locked gift page with a photo, a hidden surprise and an animated reveal, shared as one link.">
    <title>Giftloft — Birthday cards worth opening.</title>
    {{-- Tab icon — the app tile, same mark on every surface. --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo/clean/appicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/clean/appicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,600;1,700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>

<body class="landing">

    @include('landing._cursor')
    @include('landing._nav')

    <main>
        @include('landing._hero')
        @include('landing._how')
        @include('landing._themes')
        @include('landing._features')
        @include('landing._final-cta')
    </main>

    @include('landing._footer')

</body>

</html>
