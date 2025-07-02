<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://use.fontawesome.com/releases/v5.11.1/css/all.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;700&family=Roboto:wght@400;700&display=swap"
        rel="stylesheet">
    <!-- Fonts -->
    <!-- <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    ])

    <!-- Scripts -->
    @livewireStyles
    @yield('styles')
</head>
<body class="overflow-hidden @if(Route::currentRouteName() == 'guest.dashboard') dashboard-pagee @endif">
<div class="d-flex justify-content-center position-fixed w-100 h-100 flex-column bg-white" id="spinerWrap"
     style="z-index: 10000;">
    <div class="spinner-border mx-auto sec-color" role="status">
        <span class="visually-hidden">Загрузка...</span>
    </div>
</div>

<div class="container-fluid min-vh-100">
    <div class="wrapper d-flex align-items-stretch flex-column flex-lg-row min-vh-100">
        <div class="container-fluid  d-block d-lg-none p-2 sticky-top bg-white border-bottom">
            <button class="navbar-toggler navbar-toggler-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                    aria-label="Toggle navigation">
                <div class="animated-hamburger"><span></span><span></span><span></span></div>
            </button>
        </div>
        <aside class="sticky-top d-lg-flex flex-column flex-shrink-0 text-white sidebar left asidebar minh-100">
            @livewire('dashboard.guest.guest-navigation-menu')
        </aside>

        <main class="row w-100 m-0 gap-4" id="content">
            @livewire('flash-component')
            {{ $slot }}
        </main>
    </div>
</div>

@livewire('dashboard.guest.guest-dashboard-restriction-modal')

@stack('modals')
@livewireScripts
@yield('scripts') {{--Important Note: Never remove this line--}}
@stack('scripts')
</body>
</html>
