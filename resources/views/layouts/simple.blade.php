<!doctype html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>{{ env('APP_NAME') }} - @yield('title')</title>

    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
    <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">

    @yield('css')
    @vite(['resources/sass/main.scss', 'resources/js/codebase/app.js'])
</head>

<body>
    <div id="page-container" class="main-content-boxed">
        <main id="main-container">
            @yield('content')
        </main>
    </div>
</body>
<script src="{{ asset('js/lib/jquery.min.js') }}"></script>
@yield('js')
</html>
