<footer class="relative text-white mt-auto overflow-hidden bg-[#021820] border-t border-emerald-500/30">
    <!-- Background Image with Ambient Gradient Overlay -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat pointer-events-none" style="background-image: url('{{ asset('images/knuckles-bg.jpg') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#02131a] via-[#031d28]/85 to-[#042431]/88 backdrop-blur-[1px] pointer-events-none"></div>

    <div class="relative container mx-auto px-4 py-10 lg:py-12">
        <!-- 4-Column Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            
            <!-- Column 1: Brand & Social Media -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Explore Laggala Logo" class="h-12 w-auto object-contain">
                    <div>
                        <h3 class="text-xl font-bold tracking-wide text-white">Explore <span class="text-emerald-400">Laggala</span></h3>
                        <p class="text-xs font-medium text-slate-300">{{ __('footer.tagline') }}</p>
                    </div>
                </div>
                
                <p class="text-xs text-slate-300 leading-relaxed">
                    {{ __('footer.description') }}
                </p>

                <!-- Social Media Links (Design එකට අනුව) -->
                <div class="flex items-center space-x-2 pt-2">
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-[#1877F2] flex items-center justify-center text-white text-xs hover:opacity-80 transition" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-[#FF0000] flex items-center justify-center text-white text-xs hover:opacity-80 transition" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-[#E4405F] flex items-center justify-center text-white text-xs hover:opacity-80 transition" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://whatsapp.com" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-[#25D366] flex items-center justify-center text-white text-xs hover:opacity-80 transition" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="w-7 h-7 rounded-full bg-slate-700 flex items-center justify-center text-white text-xs hover:opacity-80 transition" aria-label="X">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="border-l-0 md:border-l border-slate-700/50 md:pl-6">
                <h4 class="text-sm font-semibold tracking-wider text-white mb-4">{{ __('footer.quick_links') }}</h4>
                <ul class="space-y-2.5 text-xs text-slate-300">
                    <li><a href="{{ route('pages.about') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-info-circle w-4 text-slate-400"></i> <span>{{ __('footer.about_us') }}</span></a></li>
                    <li><a href="{{ route('explore.index') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-home w-4 text-slate-400"></i> <span>{{ __('footer.explore_home') }}</span></a></li>
                    <li><a href="{{ route('explore.destinations.index') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-compass w-4 text-slate-400"></i> <span>{{ __('footer.travel_guide') }}</span></a></li>
                    <li><a href="{{ route('community.organizations.index') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-hotel w-4 text-slate-400"></i> <span>{{ __('footer.hotels_stays') }}</span></a></li>
                    <li><a href="{{ route('community.organizations.index') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-utensils w-4 text-slate-400"></i> <span>{{ __('footer.restaurants_dining') }}</span></a></li>
                    <li><a href="{{ route('explore.map') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-map-marked-alt w-4 text-slate-400"></i> <span>{{ __('footer.interactive_map') }}</span></a></li>
                </ul>
            </div>

            <!-- Column 3: Info Pages -->
            <div class="border-l-0 lg:border-l border-slate-700/50 lg:pl-6">
                <h4 class="text-sm font-semibold tracking-wider text-white mb-4">{{ __('footer.community_hub') }}</h4>
                <ul class="space-y-2.5 text-xs text-slate-300">
                    <li><a href="{{ route('community.blog.index') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-newspaper w-4 text-slate-400"></i> <span>{{ __('footer.community_blog') }}</span></a></li>
                    <li><a href="{{ route('community.events.index') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-calendar-alt w-4 text-slate-400"></i> <span>{{ __('footer.local_events') }}</span></a></li>
                    <li><a href="{{ route('pages.contributor-guidelines') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-file-alt w-4 text-slate-400"></i> <span>{{ __('footer.contributor_guidelines') }}</span></a></li>
                    <li><a href="{{ route('pages.contributors') }}" class="hover:text-emerald-400 transition flex items-center space-x-2"><i class="fas fa-users w-4 text-slate-400"></i> <span>{{ __('footer.our_contributors') }}</span></a></li>
                </ul>
                <div class="mt-4">
                    @guest
                        <a href="{{ route('register') }}" class="inline-block bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium px-3.5 py-2 rounded-md shadow transition">
                            {{ __('footer.become_contributor') }}
                        </a>
                    @else
                        <a href="{{ route('community.dashboard') }}" class="inline-block bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium px-3.5 py-2 rounded-md shadow transition">
                            {{ __('footer.become_contributor') }}
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Column 4: Contact Us & Emergency Button -->
            <div class="border-l-0 lg:border-l border-slate-700/50 lg:pl-6">
                <h4 class="text-sm font-semibold tracking-wider text-white mb-4">{{ __('footer.contact_us') }}</h4>
                <ul class="space-y-2.5 text-xs text-slate-300 mb-5">
                    <li class="flex items-start space-x-2">
                        <i class="fas fa-map-marker-alt text-slate-400 mt-0.5"></i>
                        <span>{{ __('footer.address') }}</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fas fa-phone-alt text-slate-400"></i>
                        <a href="tel:0662275200" class="hover:text-emerald-400 transition">066 227 5200</a>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fas fa-envelope text-slate-400"></i>
                        <a href="mailto:explorelaggala@gmail.com" class="hover:text-emerald-400 transition">explorelaggala@gmail.com</a>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fas fa-clock text-slate-400"></i>
                        <span>{{ __('footer.office_hours') }}</span>
                    </li>
                </ul>

                
                <!-- Emergency Contacts Button -->
               <a href="{{ Route::has('emergency.contacts') ? route('emergency.contacts') : '#' }}" class="flex items-center justify-between bg-emerald-600/90 hover:bg-emerald-600 text-white rounded-full p-2 pr-4 shadow-lg border border-emerald-400/30 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-300/20 flex items-center justify-center">
                            <i class="fas fa-headset text-white text-sm"></i>
                        </div>
                        <div>
                            <div class="text-xs font-bold leading-tight">{{ __('footer.emergency_contacts') }}</div>
                            <div class="text-[10px] text-emerald-100">{{ __('footer.emergency_services') }}</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            </div>

        </div>

        <!-- Bottom Sub-Bar -->
        <div class="pt-4 border-t border-slate-700/50 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-400 gap-2">
            <div>
                {{ __('footer.copyright', ['year' => date('Y')]) }}
            </div>
            <div class="flex items-center space-x-2">
                <span class="font-medium text-emerald-300">{{ __('footer.discover_slogan') }}</span>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-5 w-auto object-contain inline-block opacity-80">
            </div>
        </div>
    </div>
</footer>