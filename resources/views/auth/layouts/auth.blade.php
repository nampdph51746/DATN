<!DOCTYPE html>
<html lang="vi" class="h-100">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'CineVN')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body class="h-100">
        @yield('content');
    <script src="{{ asset('assets/js/vendor.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>