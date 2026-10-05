<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore Laggala — Local Information, Tourism & Community Platform">
    <title>@yield('title', 'Explore Laggala') — Discover • Explore • Experience</title>

    <!-- Favicon / Title Logo -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <!-- Local Bundled Assets (Bootstrap 5, Icons, FontAwesome, Leaflet & Tailwind via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .dropdown-submenu { position: relative; }
        .dropdown-submenu .dropdown-menu { top: 0; left: 100%; margin-top: -1px; }
        @media (max-width: 991.98px) {
            .dropdown-submenu .dropdown-menu { left: 0; }
        }
        .nav-link { font-weight: 500; }

        /* Override Tailwind CDN .collapse { visibility: collapse } conflict */
        #mainNavbar,
        .navbar-collapse,
        .navbar-collapse.collapse,
        #mainNavbar.collapse {
            visibility: visible !important;
        }
        @media (min-width: 992px) {
            #mainNavbar,
            .navbar-expand-lg .navbar-collapse,
            .navbar-expand-lg .navbar-collapse.collapse {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
        }
        @media (max-width: 991.98px) {
            #mainNavbar.collapse:not(.show) {
                display: none !important;
            }
            #mainNavbar.collapse.show {
                display: block !important;
                visibility: visible !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col font-sans">

    {{-- Shared Navigation --}}
    @include('partials.navbar')

    {{-- MAIN CONTENT --}}
    <main class="flex-grow-1 flex-1">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Shared Footer --}}
    @include('partials.footer')


    <!-- Multi-level & Resilient Dropdown Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.dropdown-submenu .dropdown-toggle').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    let nextEl = this.nextElementSibling;
                    if (nextEl && nextEl.classList.contains('dropdown-menu')) {
                        nextEl.classList.toggle('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
