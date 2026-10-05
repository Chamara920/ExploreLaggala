{{-- ============================================================
     NAVBAR — ExploreLaggala  |  Premium redesign
     ============================================================ --}}
@php
    $isHomePage = request()->routeIs('home') || (request()->is('/') && !request()->is('explore*'));
    $navLocale = session('locale', app()->getLocale() ?: 'si');
    $langOptions = [
        'si' => ['name' => 'සිංහල', 'code' => 'SI', 'label' => 'Sinhala'],
        'en' => ['name' => 'English', 'code' => 'EN', 'label' => 'English'],
        'ta' => ['name' => 'தமிழ்', 'code' => 'TA', 'label' => 'Tamil'],
    ];
    $currentLangData = $langOptions[$navLocale] ?? $langOptions['si'];
@endphp

<div id="siteHeaderSticky" class="site-header-sticky sticky-top w-100">

{{-- ========================================================
     TOP BAR: SOLID BRAND & SEARCH & LANG & USER
     (Fixed, solid dark background, non-translucent)
     ======================================================== --}}
<header id="topBrandBar" class="top-brand-bar">
    <div class="container-fluid px-3 px-lg-5 d-flex align-items-center justify-content-between py-1 py-lg-2">

        {{-- BRAND (LEFT) --}}
        <a class="navbar-brand d-flex align-items-center gap-2 m-0 p-0" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="{{ __('navbar.brand_title') }}" class="nav-logo-image">
            <span class="nav-logo-text">
                Explore<span class="nav-brand-green">Laggala</span>
            </span>
        </a>

        {{-- MOBILE TOGGLER (RIGHT - MOBILE ONLY) --}}
        <div class="d-flex d-lg-none align-items-center gap-2">
            <button class="navbar-toggler border-0 shadow-none p-1 text-white" type="button"
                    data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-bar"></span>
                <span class="toggler-bar"></span>
                <span class="toggler-bar"></span>
            </button>
        </div>

        {{-- DESKTOP TOP-RIGHT: SEARCH BAR + LANGUAGE + USER ACCOUNT --}}
        <div class="nav-top-actions d-none d-lg-flex align-items-center gap-3">

            {{-- SEARCH BAR --}}
            <form class="d-flex m-0" action="{{ route('explore.index') }}" method="GET" role="search">
                <div class="nav-search-wrap">
                    <i class="bi bi-search nav-search-icon"></i>
                    <input type="search" name="search" class="nav-search-input" placeholder="{{ __('navbar.search_placeholder') }}" aria-label="Search">
                </div>
            </form>

            {{-- LANGUAGE SWITCHER --}}
            <div class="dropdown nav-lang-dropdown">
                <button class="nav-lang-btn dropdown-toggle" type="button" id="langDropdownBtn"
                        data-bs-toggle="dropdown" aria-expanded="false" title="Language / භාෂාව තෝරන්න">
                    <i class="bi bi-translate text-emerald-400"></i>
                    <span class="nav-lang-current">{{ $currentLangData['name'] }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end nav-dropdown animate-dropdown nav-lang-menu" aria-labelledby="langDropdownBtn">
                    <li class="dropdown-label">{{ __('navbar.select_language') }}</li>
                    @foreach($langOptions as $code => $opt)
                        <li>
                            <a class="dropdown-item nav-lang-item {{ $navLocale === $code ? 'active' : '' }}"
                               href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}">
                                <span class="nav-lang-badge">{{ $opt['code'] }}</span>
                                <span class="di-text">
                                    <strong>{{ $opt['name'] }}</strong>
                                    <small>{{ $opt['label'] }}</small>
                                </span>
                                @if($navLocale === $code)
                                    <i class="bi bi-check-circle-fill ms-auto text-emerald-400 font-bold"></i>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- USER AVATAR / ACCOUNT BUTTON --}}
            <div class="dropdown user-dropdown">
                <button class="user-avatar-btn" id="userDropdownBtn"
                        data-bs-toggle="dropdown"
                        aria-expanded="false" aria-label="User menu">
                    @auth
                        <span class="avatar-circle avatar-loggedin">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="avatar-online-dot"></span>
                    @else
                        <span class="avatar-circle avatar-guest">
                            <i class="bi bi-person-fill"></i>
                        </span>
                    @endauth
                </button>

                {{-- LOGGED-IN DROPDOWN --}}
                @auth
                <div class="dropdown-menu dropdown-menu-end user-panel animate-dropdown" aria-labelledby="userDropdownBtn">
                    <div class="user-panel-header">
                        <span class="user-panel-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <div class="user-panel-info">
                            <div class="user-panel-name">{{ auth()->user()->name }}</div>
                            <div class="user-panel-email">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="user-panel-divider"></div>
                    <a class="user-panel-item" href="{{ route('dashboard') }}">
                        <span class="upi-icon bg-emerald-soft"><i class="bi bi-speedometer2"></i></span>
                        <span class="upi-text"><strong>{{ __('navbar.dashboard') }}</strong><small>{{ __('navbar.dashboard_desc') }}</small></span>
                    </a>
                    <a class="user-panel-item" href="{{ route('profile.edit') }}">
                        <span class="upi-icon bg-blue-soft"><i class="bi bi-person-circle"></i></span>
                        <span class="upi-text"><strong>{{ __('navbar.my_profile') }}</strong><small>{{ __('navbar.my_profile_desc') }}</small></span>
                    </a>
                    <div class="user-panel-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="p-0 m-0">
                        @csrf
                        <button type="submit" class="user-panel-item user-panel-logout w-100 text-start border-0 bg-transparent">
                            <span class="upi-icon bg-red-soft"><i class="bi bi-box-arrow-right"></i></span>
                            <span class="upi-text"><strong>{{ __('navbar.sign_out') }}</strong><small>{{ __('navbar.sign_out_desc') }}</small></span>
                        </button>
                    </form>
                </div>

                {{-- GUEST DROPDOWN --}}
                @else
                <div class="dropdown-menu dropdown-menu-end user-panel animate-dropdown" aria-labelledby="userDropdownBtn">
                    <div class="user-panel-guest-header">
                        <span class="guest-icon-wrap"><i class="bi bi-person-circle"></i></span>
                        <div class="guest-header-text">
                            <strong>{{ __('navbar.welcome_guest') }}</strong>
                            <p>{{ __('navbar.welcome_guest_desc') }}</p>
                        </div>
                    </div>
                    <div class="user-panel-divider"></div>
                    <div class="user-panel-guest-actions">
                        <a href="{{ route('login') }}" class="guest-btn guest-btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('navbar.sign_in') }}
                        </a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="guest-btn guest-btn-register">
                            <i class="bi bi-person-plus me-2"></i>{{ __('navbar.create_account') }}
                        </a>
                        @endif
                    </div>
                    <div class="user-panel-divider"></div>
                    <div class="guest-features">
                        <div class="guest-feature-item"><i class="bi bi-heart-fill"></i> {{ __('navbar.guest_feature_fav') }}</div>
                        <div class="guest-feature-item"><i class="bi bi-star-fill"></i> {{ __('navbar.guest_feature_rev') }}</div>
                        <div class="guest-feature-item"><i class="bi bi-chat-dots-fill"></i> {{ __('navbar.guest_feature_com') }}</div>
                    </div>
                </div>
                @endauth
            </div>

        </div>
    </div>
</header>

{{-- ========================================================
     NAVIGATION BAR: COMPACT SINGLE-LINE MENU
     (Size reduced, strictly 1 single line on desktop, sticky with frosted glass)
     ======================================================== --}}
<nav id="mainNav" class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid px-3 px-lg-5">
        <div class="collapse navbar-collapse w-100" id="mainNavbar">

            {{-- MOBILE-ONLY ACTIONS (SEARCH, LANGUAGE, USER) --}}
            <div class="d-lg-none w-100 pt-2 pb-3 mb-2 border-bottom border-white/10">
                <form class="mb-3" action="{{ route('explore.index') }}" method="GET" role="search">
                    <div class="nav-search-wrap w-100">
                        <i class="bi bi-search nav-search-icon"></i>
                        <input type="search" name="search" class="nav-search-input w-100" placeholder="{{ __('navbar.search_placeholder') }}" aria-label="Search">
                    </div>
                </form>
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div class="dropdown nav-lang-dropdown flex-grow-1">
                        <button class="nav-lang-btn w-100 justify-content-center dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-translate text-emerald-400"></i>
                            <span>{{ $currentLangData['name'] }}</span>
                        </button>
                        <ul class="dropdown-menu nav-dropdown animate-dropdown w-100">
                            @foreach($langOptions as $code => $opt)
                                <li>
                                    <a class="dropdown-item nav-lang-item {{ $navLocale === $code ? 'active' : '' }}"
                                       href="{{ request()->fullUrlWithQuery(['lang' => $code]) }}">
                                        <span class="nav-lang-badge">{{ $opt['code'] }}</span>
                                        <span class="di-text"><strong>{{ $opt['name'] }}</strong></span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1 rounded-pill px-3">
                            <i class="bi bi-speedometer2"></i> {{ __('navbar.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light d-flex align-items-center gap-1 rounded-pill px-3">
                            <i class="bi bi-box-arrow-in-right"></i> {{ __('navbar.sign_in') }}
                        </a>
                    @endauth
                </div>
            </div>

            {{-- PRIMARY NAV LINKS (STRICTLY 1 LINE ON DESKTOP) --}}
            <ul class="navbar-nav nav-single-line w-100 d-flex flex-nowrap align-items-center justify-content-start gap-1 gap-xl-2 py-0 my-0">

                {{-- HOME --}}
                <li class="nav-item">
                    <a class="nav-pill {{ request()->routeIs('home') || (request()->is('/') && !request()->is('explore*')) ? 'active' : '' }}"
                       href="{{ route('home') }}">
                        <i class="bi bi-house-door"></i>
                        <span>{{ __('navbar.home') }}</span>
                    </a>
                </li>

                {{-- EXPLORE --}}
                <li class="nav-item dropdown">
                    <a class="nav-pill dropdown-toggle {{ request()->is('explore*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown" role="button">
                        <i class="bi bi-compass"></i><span>{{ __('navbar.explore') }}</span>
                    </a>
                    <ul class="dropdown-menu nav-dropdown animate-dropdown">
                        <li class="dropdown-label">{{ __('navbar.discover_laggala') }}</li>
                        <li><a class="dropdown-item" href="{{ route('explore.destinations.index') }}">
                            <span class="di-icon bg-emerald-soft"><i class="bi bi-signpost-2"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.destinations') }}</strong><small>{{ __('navbar.destinations_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('explore.map') }}">
                            <span class="di-icon bg-blue-soft"><i class="bi bi-map"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.interactive_map') }}</strong><small>{{ __('navbar.interactive_map_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('explore.culture-heritage.index') }}">
                            <span class="di-icon bg-amber-soft"><i class="bi bi-bank"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.culture_heritage') }}</strong><small>{{ __('navbar.culture_heritage_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('explore.outdoor-adventure.index') }}">
                            <span class="di-icon bg-teal-soft"><i class="bi bi-tree"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.outdoor_adventure') }}</strong><small>{{ __('navbar.outdoor_adventure_desc') }}</small></span>
                        </a></li>
                    </ul>
                </li>

                {{-- PLAN YOUR TRIP --}}
                <li class="nav-item dropdown">
                    <a class="nav-pill dropdown-toggle {{ request()->is('plan*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown" role="button">
                        <i class="bi bi-map-fill"></i><span>{{ __('navbar.plan_trip') }}</span>
                    </a>
                    <ul class="dropdown-menu nav-dropdown animate-dropdown">
                        <li class="dropdown-label">{{ __('navbar.trip_planning') }}</li>
                        <li><a class="dropdown-item {{ request()->routeIs('plan.smart-trip-planner.*') || request()->routeIs('plan.smart-planner.*') ? 'active' : '' }}" href="{{ route('plan.smart-trip-planner.index') }}">
                            <span class="di-icon bg-purple-soft"><i class="bi bi-compass"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.smart_trip_planner') }}</strong><small>{{ __('navbar.smart_trip_planner_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('plan.travel-guide.*') ? 'active' : '' }}" href="{{ route('plan.travel-guide.index') }}">
                            <span class="di-icon bg-emerald-soft"><i class="bi bi-journal-text"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.travel_guide') }}</strong><small>{{ __('navbar.travel_guide_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('plan.transport.*') ? 'active' : '' }}" href="{{ route('plan.transport.index') }}">
                            <span class="di-icon bg-blue-soft"><i class="bi bi-bus-front"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.public_transport') }}</strong><small>{{ __('navbar.public_transport_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('plan.weather.*') ? 'active' : '' }}" href="{{ route('plan.weather.index') }}">
                            <span class="di-icon bg-sky-soft"><i class="bi bi-cloud-sun"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.weather_safety') }}</strong><small>{{ __('navbar.weather_safety_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->routeIs('plan.coverage.*') ? 'active' : '' }}" href="{{ route('plan.coverage.index') }}">
                            <span class="di-icon bg-orange-soft"><i class="bi bi-reception-4"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.mobile_coverage') }}</strong><small>{{ __('navbar.mobile_coverage_desc') }}</small></span>
                        </a></li>
                    </ul>
                </li>

                {{-- SERVICES --}}
                <li class="nav-item dropdown">
                    <a class="nav-pill dropdown-toggle {{ request()->is('services*') ? 'active' : '' }}" href="{{ route('services.institutions.index') }}" data-bs-toggle="dropdown" role="button">
                        <i class="bi bi-briefcase"></i><span>{{ __('navbar.services') }}</span>
                    </a>
                    <ul class="dropdown-menu nav-dropdown animate-dropdown">
                        <li class="dropdown-label">{{ __('navbar.local_services') }}</li>
                        <li><a class="dropdown-item {{ request()->routeIs('services.institutions.*') ? 'active' : '' }}" href="{{ route('services.institutions.index') }}">
                            <span class="di-icon bg-slate-soft"><i class="bi bi-building"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.government_institutions') }}</strong><small>{{ __('navbar.government_institutions_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->is('services/health*') ? 'active' : '' }}" href="{{ route('services.places.section', 'health') }}">
                            <span class="di-icon bg-red-soft"><i class="bi bi-hospital"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.health_services') }}</strong><small>{{ __('navbar.health_services_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->is('services/shops-businesses*') ? 'active' : '' }}" href="{{ route('services.places.section', 'shops-businesses') }}">
                            <span class="di-icon bg-emerald-soft"><i class="bi bi-shop"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.shops_businesses') }}</strong><small>{{ __('navbar.shops_businesses_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->is('services/banks-atms*') ? 'active' : '' }}" href="{{ route('services.places.section', 'banks-atms') }}">
                            <span class="di-icon bg-blue-soft"><i class="bi bi-bank2"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.banks_atms') }}</strong><small>{{ __('navbar.banks_atms_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->is('services/fuel-ev*') ? 'active' : '' }}" href="{{ route('services.places.section', 'fuel-ev') }}">
                            <span class="di-icon bg-amber-soft"><i class="bi bi-fuel-pump"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.fuel_ev') }}</strong><small>{{ __('navbar.fuel_ev_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->is('services/education*') ? 'active' : '' }}" href="{{ route('services.places.section', 'education') }}">
                            <span class="di-icon bg-purple-soft"><i class="bi bi-mortarboard"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.education') }}</strong><small>{{ __('navbar.education_desc') }}</small></span>
                        </a></li>
                    </ul>
                </li>

                {{-- STAY & EAT --}}
                <li class="nav-item dropdown">
                    <a class="nav-pill dropdown-toggle {{ request()->is('stay-eat*') ? 'active' : '' }}" href="{{ route('stay-eat.index') }}" data-bs-toggle="dropdown" role="button">
                        <i class="bi bi-cup-hot"></i><span>{{ __('navbar.stay_eat') }}</span>
                    </a>
                    <ul class="dropdown-menu nav-dropdown animate-dropdown">
                        <li class="dropdown-label">{{ __('navbar.accommodation_food') }}</li>
                        <li><a class="dropdown-item {{ request()->routeIs('stay-eat.index') ? 'active' : '' }}" href="{{ route('stay-eat.index') }}">
                            <span class="di-icon bg-emerald-soft"><i class="bi bi-compass"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.stay_eat_hub') }}</strong><small>{{ __('navbar.stay_eat_hub_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->is('stay-eat/accommodation*') ? 'active' : '' }}" href="{{ route('stay-eat.section', 'accommodation') }}">
                            <span class="di-icon bg-blue-soft"><i class="bi bi-building-check"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.accommodation') }}</strong><small>{{ __('navbar.accommodation_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->is('stay-eat/restaurants-cafes*') ? 'active' : '' }}" href="{{ route('stay-eat.section', 'restaurants-cafes') }}">
                            <span class="di-icon bg-orange-soft"><i class="bi bi-egg-fried"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.restaurants_cafes') }}</strong><small>{{ __('navbar.restaurants_cafes_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->is('stay-eat/local-food*') ? 'active' : '' }}" href="{{ route('stay-eat.section', 'local-food') }}">
                            <span class="di-icon bg-amber-soft"><i class="bi bi-basket"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.local_food') }}</strong><small>{{ __('navbar.local_food_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item {{ request()->is('stay-eat/outdoor-dining*') ? 'active' : '' }}" href="{{ route('stay-eat.section', 'outdoor-dining') }}">
                            <span class="di-icon bg-teal-soft"><i class="bi bi-tent"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.outdoor_dining') }}</strong><small>{{ __('navbar.outdoor_dining_desc') }}</small></span>
                        </a></li>
                    </ul>
                </li>

                {{-- EMERGENCY --}}
                <li class="nav-item dropdown">
                    <a class="nav-pill nav-emergency dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button">
                        <i class="bi bi-exclamation-triangle-fill"></i><span>{{ __('navbar.emergency') }}</span>
                    </a>
                    <ul class="dropdown-menu nav-dropdown animate-dropdown">
                        <li class="dropdown-label">{{ __('navbar.emergency_services') }}</li>
                        <li><a class="dropdown-item" href="{{ route('emergency.contacts') }}">
                            <span class="di-icon bg-red-soft"><i class="bi bi-telephone-fill"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.emergency_contacts') }}</strong><small>{{ __('navbar.emergency_contacts_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('emergency.hospitals') }}">
                            <span class="di-icon bg-red-soft"><i class="bi bi-hospital"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.hospitals') }}</strong><small>{{ __('navbar.hospitals_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('emergency.police') }}">
                            <span class="di-icon bg-blue-soft"><i class="bi bi-shield-lock"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.police_stations') }}</strong><small>{{ __('navbar.police_stations_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('emergency.wildlife-forest') }}">
                            <span class="di-icon bg-teal-soft"><i class="bi bi-tree-fill"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.wildlife_forest') }}</strong><small>{{ __('navbar.wildlife_forest_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('emergency.vehicle-assistance') }}">
                            <span class="di-icon bg-amber-soft"><i class="bi bi-car-front"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.vehicle_assistance') }}</strong><small>{{ __('navbar.vehicle_assistance_desc') }}</small></span>
                        </a></li>
                    </ul>
                </li>

                {{-- COMMUNITY --}}
                <li class="nav-item dropdown">
                    <a class="nav-pill dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button">
                        <i class="bi bi-people"></i><span>{{ __('navbar.community') }}</span>
                    </a>
                    <ul class="dropdown-menu nav-dropdown animate-dropdown">
                        <li class="dropdown-label">{{ __('navbar.connect_share') }}</li>
                        <li><a class="dropdown-item" href="{{ route('community.news.index') }}">
                            <span class="di-icon bg-slate-soft"><i class="bi bi-newspaper"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.news') }}</strong><small>{{ __('navbar.news_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('community.events.index') }}">
                            <span class="di-icon bg-purple-soft"><i class="bi bi-calendar-event"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.events') }}</strong><small>{{ __('navbar.events_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('community.organizations.index') }}">
                            <span class="di-icon bg-emerald-soft"><i class="bi bi-diagram-3"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.organizations') }}</strong><small>{{ __('navbar.organizations_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('community.forum.index') }}">
                            <span class="di-icon bg-blue-soft"><i class="bi bi-chat-dots"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.forum') }}</strong><small>{{ __('navbar.forum_desc') }}</small></span>
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('community.blog.index') }}">
                            <span class="di-icon bg-amber-soft"><i class="bi bi-pencil-square"></i></span>
                            <span class="di-text"><strong>{{ __('navbar.blog') }}</strong><small>{{ __('navbar.blog_desc') }}</small></span>
                        </a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

</div>{{-- END #siteHeaderSticky --}}

<style>
/* =========================================================
   EXPLORE LAGGALA — GREEN CITY NAVIGATION THEME
   TRANSLUCENT GLASSMORPHISM NAVIGATION THEME
   Deep Emerald Obsidian Glass + Crystal Blur
   ========================================================= */

/* Guaranteed visibility - override Tailwind CDN .collapse conflict */
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

/* =========================================================
   STICKY HEADER WRAPPER (BOTH BRAND BAR & NAV STAY STICKY)
   ========================================================= */
.site-header-sticky {
    position: sticky;
    top: 0;
    z-index: 1040;
    width: 100%;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
    transition: box-shadow 0.3s ease;
}

/* =========================================================
   TOP BRAND BAR (SOLID, PERMANENT, NON-TRANSLUCENT)
   ========================================================= */
.top-brand-bar {
    background: #061812;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    position: relative;
    z-index: 1045;
}

/* =========================================================
   COMPACT SINGLE-LINE NAVIGATION MENU BAR
   ========================================================= */
#mainNav {
    padding: 0.42rem 0;
    width: 100%;
    background: rgba(6, 20, 16, 0.94);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    overflow: visible !important;
}

/* Strictly 1 single line on desktop - ensure overflow is visible so dropdowns are never clipped */
.nav-single-line {
    display: flex !important;
    flex-wrap: nowrap !important;
}

@media (min-width: 992px) {
    #mainNavbar,
    #mainNav,
    .nav-single-line {
        overflow: visible !important;
    }
}


/* =========================================================
   BRAND
   ========================================================= */

.nav-logo-image {
    height: 38px;
    width: auto;
    max-height: 40px;
    object-fit: contain;
    filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.35));
    transition: transform 0.2s ease;
}

.navbar-brand:hover .nav-logo-image {
    transform: scale(1.05);
}

.nav-logo-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 36px;
    height: 36px;

    background:
        linear-gradient(
            135deg,
            #34d399,
            #06b6d4
        );

    border-radius: 10px;

    color: #ffffff;

    font-size: 1rem;

    box-shadow:
        0 3px 12px rgba(6, 182, 212, 0.28);

    flex-shrink: 0;
}


.nav-logo-text {
    font-size: 1.08rem;
    font-weight: 750;
    color: #ffffff;
    line-height: 1;
    letter-spacing: 0.1px;
}


.nav-brand-green {
    color: #6ee7b7;
}


/* =========================================================
   MOBILE TOGGLER
   ========================================================= */

.toggler-bar {
    display: block;

    width: 23px;
    height: 2px;

    background: #ffffff;

    border-radius: 2px;

    margin: 5px 0;

    transition: all 0.3s ease;
}


.navbar-toggler[aria-expanded="true"]
.toggler-bar:nth-child(1) {
    transform: translateY(7px) rotate(45deg);
}

.navbar-toggler[aria-expanded="true"]
.toggler-bar:nth-child(2) {
    opacity: 0;
    transform: scaleX(0);
}

.navbar-toggler[aria-expanded="true"]
.toggler-bar:nth-child(3) {
    transform: translateY(-7px) rotate(-45deg);
}


/* =========================================================
   MAIN NAVIGATION
   ========================================================= */

.nav-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: rgba(255,255,255,0.92) !important;
    font-size: 0.88rem;
    font-weight: 600;
    padding: 0.35rem 0.65rem;
    border-radius: 8px;
    transition:
        background 0.15s ease,
        color 0.15s ease,
        transform 0.15s ease;
    white-space: nowrap !important;
    text-decoration: none;
    line-height: 1.3;
}

.nav-pill i {
    font-size: 0.92rem;
    opacity: 0.88;
    transition:
        color 0.15s ease,
        transform 0.15s ease;
}


/* Hover */

.nav-pill:hover,
.nav-pill.show {
    color: #ffffff !important;

    background:
        rgba(255,255,255,0.12);

    transform: translateY(-1px);
}


.nav-pill:hover i {
    color: #67e8f9;
    transform: translateY(-1px);
}


/* =========================================================
   ACTIVE MENU
   ========================================================= */

/*
   Important:
   Do NOT use dark green for active Home.
   Use a visible green/teal surface.
*/

.nav-pill.active {
    color: #ffffff !important;

    background:
        linear-gradient(
            135deg,
            rgba(16,185,129,0.95),
            rgba(8,145,178,0.95)
        );

    font-weight: 650;

    box-shadow:
        0 3px 12px rgba(6, 182, 212, 0.20);

    border: 1px solid rgba(255,255,255,0.12);
}


.nav-pill.active i {
    opacity: 1;
    color: #ffffff;
}


/* Dropdown arrow */

.nav-pill.dropdown-toggle::after {
    margin-left: 2px;

    opacity: 0.7;

    font-size: 0.68rem;

    vertical-align: middle;
}


/* =========================================================
   EMERGENCY
   ========================================================= */

.nav-emergency {
    color: #ffffff !important;

    background:
        rgba(239, 68, 68, 0.10);
}


.nav-emergency:hover {
    color: #ffffff !important;

    background:
        rgba(239,68,68,0.25) !important;
}


/* =========================================================
   DROPDOWN
   ========================================================= */

.nav-dropdown {
    z-index: 1060 !important;
    position: absolute !important;
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 16px;
    box-shadow:
        0 18px 45px rgba(0, 0, 0, 0.45),
        0 2px 10px rgba(0, 0, 0, 0.2);
    padding: 0.45rem;
    min-width: 270px;
    background: rgba(9, 24, 19, 0.94);
    backdrop-filter: blur(24px) saturate(180%);
    -webkit-backdrop-filter: blur(24px) saturate(180%);
}

.nav-dropdown.show,
.user-panel.show {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
}

@media (min-width: 992px) {
    .navbar-expand-lg .nav-item.dropdown {
        position: relative !important;
    }
    .navbar-expand-lg .nav-item.dropdown:hover > .nav-dropdown,
    .navbar-expand-lg .dropdown.nav-lang-dropdown:hover > .nav-dropdown,
    .navbar-expand-lg .dropdown.user-dropdown:hover > .user-panel {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
    }
}

/* =========================================================
   LANGUAGE SWITCHER
   ========================================================= */

.nav-lang-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.20);
    border-radius: 12px;
    padding: 0.42rem 0.75rem;
    color: #ffffff !important;
    font-size: 0.82rem;
    font-weight: 650;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: all 0.2s ease;
    cursor: pointer;
    text-decoration: none;
}

.nav-lang-btn:hover,
.nav-lang-btn.show {
    background: rgba(255, 255, 255, 0.22);
    border-color: rgba(16, 185, 129, 0.5);
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.nav-lang-btn::after {
    margin-left: 2px;
    font-size: 0.65rem;
    vertical-align: middle;
    opacity: 0.75;
}

.nav-lang-current {
    font-weight: 700;
    letter-spacing: 0.2px;
}

.nav-lang-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: rgba(16, 185, 129, 0.20);
    color: #34d399;
    border: 1px solid rgba(52, 211, 153, 0.25);
    font-weight: 800;
    font-size: 0.72rem;
    flex-shrink: 0;
}

.nav-lang-menu {
    min-width: 220px !important;
}

.nav-lang-item.active {
    background: rgba(16, 185, 129, 0.22) !important;
    color: #34d399 !important;
}


.animate-dropdown {
    animation:
        dropIn 0.18s cubic-bezier(0.23,1,0.32,1)
        forwards;

    transform-origin: top center;
}


@keyframes dropIn {

    from {
        opacity: 0;
        transform: translateY(-8px) scale(0.97);
    }

    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

}


.dropdown-label {
    font-size: 0.67rem;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: 0.08em;

    color: #94a3b8;

    padding:
        0.35rem
        0.6rem
        0.22rem;
}


.nav-dropdown .dropdown-item {
    display: flex;

    align-items: center;

    gap: 10px;

    padding: 0.48rem 0.55rem;

    border-radius: 10px;

    transition:
        background 0.15s ease,
        transform 0.15s ease;

    text-decoration: none;
    color: #e2e8f0;
}


.nav-dropdown .dropdown-item:hover,
.nav-dropdown .dropdown-item:focus {
    background: rgba(16, 185, 129, 0.18);
    color: #ffffff;
    transform: translateX(3px);
}


.nav-dropdown .dropdown-item:focus {
    outline: none;
}


/* =========================================================
   DROPDOWN ICONS
   ========================================================= */

.di-icon {
    display: flex;

    align-items: center;
    justify-content: center;

    width: 34px;
    height: 34px;

    border-radius: 9px;

    font-size: 0.95rem;

    flex-shrink: 0;
}


.di-text {
    display: flex;

    flex-direction: column;

    line-height: 1.2;
}


.di-text strong {
    font-size: 0.81rem;
    color: #ffffff;
}


.di-text small {
    font-size: 0.69rem;
    color: #94a3b8;
}

.nav-dropdown .dropdown-item:hover .di-text small {
    color: #cbd5e1;
}


/* Green / Blue colour system */

.bg-emerald-soft {
    background: #d1fae5;
    color: #047857;
}

.bg-blue-soft {
    background: #dbeafe;
    color: #1d4ed8;
}

.bg-amber-soft {
    background: #fef3c7;
    color: #92400e;
}

.bg-teal-soft {
    background: #ccfbf1;
    color: #0f766e;
}

.bg-purple-soft {
    background: #ede9fe;
    color: #6d28d9;
}

.bg-orange-soft {
    background: #ffedd5;
    color: #c2410c;
}

.bg-red-soft {
    background: #fee2e2;
    color: #b91c1c;
}

.bg-sky-soft {
    background: #e0f2fe;
    color: #0369a1;
}

.bg-slate-soft {
    background: #f1f5f9;
    color: #334155;
}


/* =========================================================
   SEARCH
   ========================================================= */

.nav-search-wrap {
    position: relative;

    display: flex;

    align-items: center;
}


.nav-search-icon {
    position: absolute;

    left: 10px;

    color: rgba(255,255,255,0.65);

    font-size: 0.8rem;

    pointer-events: none;
}


.nav-search-input {
    background:
        rgba(255,255,255,0.10);

    border:
        1px solid rgba(255,255,255,0.20);

    border-radius: 20px;

    color: #ffffff;

    font-size: 0.81rem;

    padding:
        0.38rem
        0.85rem
        0.38rem
        2rem;

    width: 155px;

    outline: none;

    transition:
        background 0.2s ease,
        width 0.3s ease,
        border-color 0.2s ease;
}


.nav-search-input::placeholder {
    color: rgba(255,255,255,0.58);
}


.nav-search-input:focus {
    background:
        rgba(255,255,255,0.17);

    border-color:
        rgba(103,232,249,0.65);

    width: 195px;

    color: #ffffff;

    box-shadow:
        0 0 0 3px rgba(6,182,212,0.10);
}


/* =========================================================
   USER AVATAR
   ========================================================= */

.user-avatar-btn {
    position: relative;

    background: transparent;

    border: none;

    padding: 2px;

    cursor: pointer;

    outline: none;

    line-height: 1;
}


.avatar-circle {
    display: flex;

    align-items: center;
    justify-content: center;

    width: 36px;
    height: 36px;

    border-radius: 50%;

    font-size: 0.9rem;

    font-weight: 700;

    transition:
        transform 0.2s,
        box-shadow 0.2s;
}


.avatar-loggedin {
    background:
        linear-gradient(
            135deg,
            #34d399,
            #06b6d4
        );

    color: #064e3b;

    box-shadow:
        0 0 0 2px rgba(110,231,183,0.35);
}


.avatar-guest {
    background:
        rgba(255,255,255,0.13);

    color:
        rgba(255,255,255,0.92);

    border:
        1.5px solid rgba(255,255,255,0.30);

    font-size: 1.08rem;
}


.user-avatar-btn:hover .avatar-circle {
    transform: scale(1.08);

    box-shadow:
        0 0 0 3px rgba(103,232,249,0.30);
}


.avatar-online-dot {
    position: absolute;

    bottom: 1px;
    right: 1px;

    width: 9px;
    height: 9px;

    background: #22c55e;

    border:
        2px solid #075e45;

    border-radius: 50%;
}


/* =========================================================
   USER DROPDOWN
   ========================================================= */

.user-panel {
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 16px;
    box-shadow:
        0 20px 50px rgba(0, 0, 0, 0.45),
        0 2px 8px rgba(0, 0, 0, 0.2);
    padding: 0;
    overflow: hidden;
    min-width: 278px;
    background: rgba(8, 24, 18, 0.90);
    backdrop-filter: blur(24px) saturate(180%);
    -webkit-backdrop-filter: blur(24px) saturate(180%);
}

.user-panel-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.12);
    margin: 0.2rem 0;
}

.user-panel-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 1rem 1.1rem 0.75rem;
    background: rgba(16, 185, 129, 0.12);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.user-panel-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #34d399, #06b6d4);
    color: #064e3b;
    font-size: 1.1rem;
    font-weight: 700;
    flex-shrink: 0;
}

.user-panel-name {
    font-size: 0.87rem;
    font-weight: 700;
    color: #ffffff;
}

.user-panel-email {
    font-size: 0.71rem;
    color: #94a3b8;
}

.user-panel-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.5rem 0.75rem;
    margin: 0.12rem 0.32rem;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.15s ease;
    cursor: pointer;
}

.user-panel-item:hover {
    background: rgba(16, 185, 129, 0.16);
}

.upi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.upi-text {
    display: flex;
    flex-direction: column;
    line-height: 1.22;
}

.upi-text strong {
    font-size: 0.81rem;
    color: #f1f5f9;
}

.upi-text small {
    font-size: 0.69rem;
    color: #94a3b8;
}

/* =========================================================
   GUEST USER
   ========================================================= */

.user-panel-guest-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 1.2rem 1.2rem 0.7rem;
    background: rgba(16, 185, 129, 0.12);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    text-align: center;
}

.guest-icon-wrap {
    font-size: 2.4rem;
    color: #34d399;
    line-height: 1;
}

.guest-header-text strong {
    font-size: 0.95rem;
    color: #ffffff;
    display: block;
    margin-bottom: 4px;
}

.guest-header-text p {
    font-size: 0.74rem;
    color: #94a3b8;
    margin: 0;
    line-height: 1.4;
}

.user-panel-guest-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 0.7rem 0.85rem;
}

.guest-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.48rem 1rem;
    border-radius: 10px;
    font-size: 0.84rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.guest-btn-login {
    background: linear-gradient(135deg, #059669, #0891b2);
    color: #ffffff;
    border: 1px solid transparent;
}

.guest-btn-login:hover {
    background: linear-gradient(135deg, #047857, #0e7490);
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(5, 150, 105, 0.35);
}

.guest-btn-register {
    background: rgba(255, 255, 255, 0.08);
    color: #34d399;
    border: 1.5px solid rgba(52, 211, 153, 0.6);
}

.guest-btn-register:hover {
    background: rgba(52, 211, 153, 0.2);
    color: #ffffff;
    transform: translateY(-1px);
}

.guest-features {
    padding: 0.45rem 0.9rem 0.8rem;
}

.guest-feature-item {
    font-size: 0.74rem;
    color: #cbd5e1;
    padding: 0.22rem 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.guest-feature-item i {
    color: #34d399;
    font-size: 0.78rem;
}

/* =========================================================
   MOBILE
   ========================================================= */

@media (min-width: 992px) and (max-width: 1280px) {
    .nav-pill {
        padding: 0.32rem 0.5rem;
        font-size: 0.82rem;
        gap: 5px;
    }
    .nav-pill i {
        font-size: 0.86rem;
    }
    .nav-logo-text {
        font-size: 1rem;
    }
    .nav-search-wrap {
        width: 120px;
    }
}

@media (max-width: 991.98px) {

    #mainNav {
        padding: 0.6rem 0.75rem;
    }

    #mainNavbar {
        max-height: 80vh;
        overflow-y: auto;
        padding: 0.85rem;
        margin-top: 0.6rem;
        background: rgba(7, 20, 16, 0.94);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
    }

    .nav-pill {
        border-radius: 9px;
        padding: 0.55rem 0.85rem;
        font-size: 0.92rem;
    }

    .nav-pill i {
        font-size: 0.96rem;
    }

    .nav-pill.active {
        background: linear-gradient(
            90deg,
            rgba(16, 185, 129, 0.85),
            rgba(8, 145, 178, 0.85)
        );
    }

    .nav-dropdown {
        border-radius: 10px;
        min-width: auto;
        position: static !important;
        transform: none !important;
    }

    .nav-search-input,
    .nav-search-input:focus {
        width: 100%;
    }

    .user-panel {
        min-width: 255px;
    }

}
</style>

<script>
(function(){
    var nav = document.getElementById('mainNav');
    if(!nav) return;
    window.addEventListener('scroll',function(){
        nav.classList.toggle('scrolled', window.scrollY > 30);
    },{passive:true});
})();
</script>