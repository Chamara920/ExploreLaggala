@php
    $currentRoute = Route::currentRouteName();
    $currentLang = $locale ?? session('locale', app()->getLocale() ?: 'si');
@endphp

<div class="bg-gradient-to-r from-red-900 via-red-800 to-rose-950 text-white py-10 px-4 mb-8 shadow-md">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 bg-red-700/60 border border-red-500/40 text-red-100 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                    <span class="w-2 h-2 rounded-full bg-red-400 animate-ping"></span>
                    @if($currentLang === 'si')
                        හදිසි සේවා සහ ක්ෂණික සහන අංශය
                    @elseif($currentLang === 'ta')
                        அவசர சேவைகள் மற்றும் உடனடி உதவி
                    @else
                        24/7 Emergency & Rapid Response
                    @endif
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight flex items-center gap-3">
                    <i class="bi bi-shield-exclamation text-amber-400"></i>
                    <span>{{ $pageTitle ?? 'Emergency Services' }}</span>
                </h1>

                <p class="text-red-100/90 text-sm md:text-base mt-2 max-w-2xl leading-relaxed">
                    {{ $pageSubtitle ?? 'Quick access to critical contact numbers, hospitals, police, forest rangers, and vehicle breakdown assistance covering Laggala, Riverston, and Knuckles wilderness.' }}
                </p>
            </div>

            {{-- Language Selector Pills --}}
            <div class="flex items-center gap-2 self-start md:self-center bg-black/30 p-1.5 rounded-xl border border-white/10 backdrop-blur-sm">
                <span class="text-xs text-red-200 font-medium px-2.5 flex items-center gap-1.5">
                    <i class="bi bi-translate"></i> Language:
                </span>
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'si']) }}"
                   class="px-3 py-1 text-xs font-semibold rounded-lg transition-all {{ $currentLang === 'si' ? 'bg-amber-400 text-gray-950 shadow' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    සිංහල
                </a>
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}"
                   class="px-3 py-1 text-xs font-semibold rounded-lg transition-all {{ $currentLang === 'en' ? 'bg-amber-400 text-gray-950 shadow' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    English
                </a>
                <a href="{{ request()->fullUrlWithQuery(['lang' => 'ta']) }}"
                   class="px-3 py-1 text-xs font-semibold rounded-lg transition-all {{ $currentLang === 'ta' ? 'bg-amber-400 text-gray-950 shadow' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    தமிழ்
                </a>
            </div>
        </div>

        {{-- Emergency Submenu Navigation Tabs --}}
        <div class="mt-8 border-t border-red-700/50 pt-6">
            <nav class="flex flex-wrap items-center gap-2">
                {{-- 1. Contacts --}}
                <a href="{{ route('emergency.contacts', ['lang' => $currentLang]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm {{ $currentRoute === 'emergency.contacts' || $currentRoute === 'emergency.index' ? 'bg-white text-red-900 shadow-md scale-[1.02]' : 'bg-red-950/60 text-red-100 hover:bg-red-800/80 border border-red-700/40' }}">
                    <i class="bi bi-telephone-fill text-amber-500"></i>
                    <span>
                        @if($currentLang === 'si') හදිසි ඇමතුම් @elseif($currentLang === 'ta') அவசர எண்கள் @else Emergency Contacts @endif
                    </span>
                </a>

                {{-- 2. Hospitals --}}
                <a href="{{ route('emergency.hospitals', ['lang' => $currentLang]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm {{ $currentRoute === 'emergency.hospitals' ? 'bg-white text-red-900 shadow-md scale-[1.02]' : 'bg-red-950/60 text-red-100 hover:bg-red-800/80 border border-red-700/40' }}">
                    <i class="bi bi-hospital text-rose-500"></i>
                    <span>
                        @if($currentLang === 'si') රෝහල් සහ සායන @elseif($currentLang === 'ta') வைத்தியசாலைகள் @else Hospitals (Gov & Private) @endif
                    </span>
                </a>

                {{-- 3. Police --}}
                <a href="{{ route('emergency.police', ['lang' => $currentLang]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm {{ $currentRoute === 'emergency.police' ? 'bg-white text-red-900 shadow-md scale-[1.02]' : 'bg-red-950/60 text-red-100 hover:bg-red-800/80 border border-red-700/40' }}">
                    <i class="bi bi-shield-lock-fill text-blue-400"></i>
                    <span>
                        @if($currentLang === 'si') පොලිස් ස්ථාන @elseif($currentLang === 'ta') காவல் நிலையங்கள் @else Police Stations @endif
                    </span>
                </a>

                {{-- 4. Wildlife & Forest --}}
                <a href="{{ route('emergency.wildlife-forest', ['lang' => $currentLang]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm {{ $currentRoute === 'emergency.wildlife-forest' ? 'bg-white text-red-900 shadow-md scale-[1.02]' : 'bg-red-950/60 text-red-100 hover:bg-red-800/80 border border-red-700/40' }}">
                    <i class="bi bi-tree-fill text-emerald-400"></i>
                    <span>
                        @if($currentLang === 'si') වනජීවී සහ වන සංරක්ෂණ @elseif($currentLang === 'ta') வனவிலங்கு & வனத்துறை @else Wildlife & Forest @endif
                    </span>
                </a>

                {{-- 5. Vehicle Assistance --}}
                <a href="{{ route('emergency.vehicle-assistance', ['lang' => $currentLang]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm {{ $currentRoute === 'emergency.vehicle-assistance' ? 'bg-white text-red-900 shadow-md scale-[1.02]' : 'bg-red-950/60 text-red-100 hover:bg-red-800/80 border border-red-700/40' }}">
                    <i class="bi bi-truck text-amber-400"></i>
                    <span>
                        @if($currentLang === 'si') වාහන ආධාර සේවා @elseif($currentLang === 'ta') வாகன உதவி @else Vehicle Assistance @endif
                    </span>
                </a>
            </nav>
        </div>
    </div>
</div>
