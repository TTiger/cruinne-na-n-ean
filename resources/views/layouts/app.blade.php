<!DOCTYPE html>
<html lang="{{ Application::lang() }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ Application::name() }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="antialiased relative bg-gray-100">
    @yield('content')
    <footer>
        {{ Application::name() }} v{{ Application::version()  }}
    </footer>
</body>
</html>
