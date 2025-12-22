<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <title>@yield('title', 'RessourceBrie')</title>

    {{-- CSS global --}}
    <link rel="stylesheet" href="{{ asset('css/sites.css') }}">
</head>

<body>

<header style="margin-bottom: 2rem;">
    <h1>@yield('title', 'RessourceBrie')</h1>
</header>

<main>
    @yield('content')
</main>

@yield('scripts')

</body>
</html>
