<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Design a personalised, PIN-locked birthday gift page in minutes — pick a theme, drop a photo, hide a surprise, share a link.">
    <title>BirthdayCard — Craft. Surprise. Celebrate.</title>

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
        @include('landing._showcase')
        @include('landing._builder-demo')
        @include('landing._creation')
        @include('landing._morph')
        @include('landing._gallery')
        @include('landing._features')
        @include('landing._final-cta')
    </main>

    @include('landing._footer')

</body>

</html>
