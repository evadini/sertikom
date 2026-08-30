<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ticketify - Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @include('components.fonts')
    @stack('styles')
</head>
<body class="bg-[#F8FAFC] text-gray-900 flex flex-col min-h-screen antialiased">

    @include('components.admin-navbar')

    <main class="flex-1 overflow-y-auto">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
