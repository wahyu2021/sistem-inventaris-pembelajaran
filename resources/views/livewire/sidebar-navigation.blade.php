<div>
    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 h-screen bg-blue-700 text-white 
               transition-all duration-300 ease-in-out overflow-y-auto overflow-x-hidden 
               {{ $sidebarOpen ? 'w-64' : 'w-20' }}">

        <div class="flex flex-col h-full">
            {{-- Tombol toggle sidebar & Logo --}}
            <div class="{{ $sidebarOpen ? 'px-4 pt-4' : 'px-4 pt-4 flex flex-col items-center' }}"> {{-- Added pt-4 for top padding --}}
                <div class="w-full flex {{ $sidebarOpen ? 'justify-between' : 'justify-center' }} items-center mb-6">
                    {{-- Logo --}}
                    <a href="{{ route('dashboard') }}" {{-- Dashboard utama akan redirect berdasarkan peran --}}
                        class="{{ !$sidebarOpen ? 'mx-auto' : '' }} flex flex-col justify-center items-center text-center">
                        @if ($sidebarOpen)
                            <img src="{{ asset('images/icon-web.png') }}" alt="Logo Aplikasi" class="h-16 w-auto mb-2">
                            {{-- Atur ukuran logo --}}
                            <span class="text-lg font-bold text-wrap leading-tight">Sistem Inventaris Pembelajaran</span>
                        @else
                            <img src="{{ asset('images/icon-web.png') }}" alt="Logo Ringkas" class="h-10 w-auto">
                            {{-- Logo lebih kecil saat sidebar tertutup --}}
                        @endif
                    </a>
                </div>

                {{-- Navigasi utama --}}
                <nav class="space-y-1 w-full">

                    {{-- Cek Peran Pengguna untuk Menampilkan Navigasi yang Sesuai --}}
                    @if (Auth::check())
                        {{-- === NAVIGASI UNTUK ADMIN === --}}
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                title="{{ !$sidebarOpen ? __('Dashboard Admin') : '' }}"
                                class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150
                                {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800' : '' }}
                                {{ !$sidebarOpen ? 'justify-center' : '' }}">
                                <x-heroicon-o-home class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                                <span
                                    class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Dashboard') }}</span>
                            </a>

                            <a href="{{ route('admin.users.index') }}"
                                title="{{ !$sidebarOpen ? __('Manajemen Pengguna') : '' }}"
                                class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150
                                {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.*') ? 'bg-blue-800' : '' }}
                                {{ !$sidebarOpen ? 'justify-center' : '' }}">
                                <x-heroicon-o-users class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                                <span
                                    class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Manajemen Pengguna') }}</span>
                            </a>

                            <a href="{{ route('admin.locations.index') }}"
                                title="{{ !$sidebarOpen ? __('Manajemen Lokasi') : '' }}"
                                class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150
                                {{ request()->routeIs('admin.locations.index') || request()->routeIs('admin.locations.*') ? 'bg-blue-800' : '' }}
                                {{ !$sidebarOpen ? 'justify-center' : '' }}">
                                <x-heroicon-o-building-office
                                    class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                                <span
                                    class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Manajemen Lokasi') }}</span>
                            </a>

                            <a href="{{ route('admin.damages.index') }}"
                                title="{{ !$sidebarOpen ? __('Laporan Kerusakan') : '' }}"
                                class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150
                                {{ request()->routeIs('admin.damages.index') || request()->routeIs('admin.damages.*') ? 'bg-blue-800' : '' }}
                                {{ !$sidebarOpen ? 'justify-center' : '' }}">
                                <x-heroicon-o-clipboard-document-list
                                    class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                                <span
                                    class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Laporan Kerusakan') }}</span>
                            </a>

                            <a href="{{ route('admin.notifications.index') }}"
                                title="{{ !$sidebarOpen ? __('Notifikasi') : '' }}"
                                class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150
                                {{ request()->routeIs('admin.notifications.index') || request()->routeIs('admin.notifications.*') ? 'bg-blue-800' : '' }}
                                {{ !$sidebarOpen ? 'justify-center' : '' }}">
                                <x-heroicon-o-bell class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                                <span
                                    class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Notifikasi') }}</span>
                            </a>

                            {{-- === NAVIGASI UNTUK MAHASISWA === --}}
                        @elseif (Auth::user()->isMahasiswa())
                            <a href="{{ route('mahasiswa.dashboard') }}"
                                title="{{ !$sidebarOpen ? __('Dashboard Mahasiswa') : '' }}"
                                class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150
                                {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-800' : '' }}
                                {{ !$sidebarOpen ? 'justify-center' : '' }}">
                                <x-heroicon-o-home class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                                <span
                                    class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Dashboard') }}</span>
                            </a>

                            <a href="{{ route('mahasiswa.locations.index') }}"
                                title="{{ !$sidebarOpen ? __('Cek & Lapor Barang') : '' }}"
                                class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150
                                {{ request()->routeIs('mahasiswa.locations.index') || request()->routeIs('mahasiswa.damages.report') ? 'bg-blue-800' : '' }} {{-- Penyesuaian warna untuk tema biru --}}
                                {{ !$sidebarOpen ? 'justify-center' : '' }}">
                                <x-heroicon-o-archive-box-arrow-down
                                    class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                                <span
                                    class="mt-0.5 {{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Cek & Lapor Barang') }}</span>
                            </a>

                            <a href="{{ route('mahasiswa.damages.my') }}"
                                title="{{ !$sidebarOpen ? __('Laporan Saya') : '' }}"
                                class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150
                                {{ request()->routeIs('mahasiswa.damages.my') ? 'bg-blue-800' : '' }}
                                {{ !$sidebarOpen ? 'justify-center' : '' }}">
                                <x-heroicon-o-document-text
                                    class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                                <span
                                    class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Laporan Saya') }}</span>
                            </a>

                            <a href="{{ route('mahasiswa.notifications.index') }}"
                                title="{{ !$sidebarOpen ? __('Notifikasi') : '' }}"
                                class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150
                                {{ request()->routeIs('mahasiswa.notifications.index') ? 'bg-blue-800' : '' }}
                                {{ !$sidebarOpen ? 'justify-center' : '' }}">
                                <x-heroicon-o-bell class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                                <span
                                    class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Notifikasi') }}</span>
                            </a>
                        @endif
                    @endif
                    {{-- Akhir dari link navigasi baru --}}
                </nav>
            </div>

            {{-- User Info & Logout --}}
            <div
                class="mt-auto border-t border-blue-600 {{ $sidebarOpen ? 'p-4' : 'p-2 pt-4 flex flex-col items-center' }}">
                @if (Auth::check())
                    @if ($sidebarOpen)
                        <div class="flex items-center mb-3">
                            <img class="h-10 w-10 rounded-full object-cover"
                                src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            <div class="ml-3">
                                <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-blue-200">{{ Str::title(Auth::user()->role) }}</p>
                            </div>
                        </div>
                    @endif
                @endif
                <div class="mt-1 space-y-1 w-full"> {{-- reduce mt if sidebar not open and no user info --}}
                    <a href="{{ route('profile.show') }}" title="{{ !$sidebarOpen ? __('Profile') : '' }}"
                        class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150 w-full
                               {{ request()->routeIs('profile.show') ? 'bg-blue-800' : '' }}
                               {{ !$sidebarOpen ? 'justify-center' : '' }}">
                        <x-heroicon-o-user-circle class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                        <span class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Profile') }}</span>
                    </a>

                    @if (Auth::check() && Laravel\Jetstream\Jetstream::hasApiFeatures() && Auth::user()->isAdmin())
                        {{-- API Tokens hanya untuk Admin --}}
                        <a href="{{ route('api-tokens.index') }}" title="{{ !$sidebarOpen ? __('API Tokens') : '' }}"
                            class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group transition-colors duration-150 w-full
                                   {{ request()->routeIs('api-tokens.index') ? 'bg-blue-800' : '' }}
                                   {{ !$sidebarOpen ? 'justify-center' : '' }}">
                            <x-heroicon-o-key class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                            <span
                                class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('API Tokens') }}</span>
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" x-data class="w-full">
                        @csrf
                        <a href="{{ route('logout') }}" @click.prevent="$root.submit();"
                            title="{{ !$sidebarOpen ? __('Log Out') : '' }}"
                            class="flex items-center py-2.5 px-3 rounded-md hover:bg-blue-600 group w-full transition-colors duration-150
                                   {{ !$sidebarOpen ? 'justify-center' : '' }}">
                            <x-heroicon-o-arrow-left-on-rectangle
                                class="h-6 w-6 shrink-0 {{ $sidebarOpen ? 'mr-3' : '' }}" />
                            <span class="{{ $sidebarOpen ? 'inline' : 'hidden' }} truncate">{{ __('Log Out') }}</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </aside>
    {{-- Konten Utama tidak ada di sini --}}
</div>
