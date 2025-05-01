<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/3896197f07.js" crossorigin="anonymous"></script>
</head>

<body class="d-flex vh-100">

    @include('livewire.layout.navigation')

    <!-- Contenido principal -->
    <main class="flex-grow-1 overflow-auto p-4">
        {{ $slot }}
    </main>
</body>

</html>
