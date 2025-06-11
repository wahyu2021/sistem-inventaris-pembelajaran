<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SISINPEM - Solusi Inventaris Jurusan Manajemen Informatika Polsri</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* CSS Anda tidak berubah, jadi saya akan mempersingkatnya di sini agar tidak terlalu panjang */
        .hero-gradient {
            background: linear-gradient(125deg, #2b6cb0, #1a365d);
        }

        .hero-bg-shape {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0) 60%);
            pointer-events: none;
        }

        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
        }

        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: linear-gradient(to bottom, rgba(26, 54, 93, 0.7), rgba(26, 54, 93, 0.8)), url("{{ asset('images/Hero-1.jpeg') }}");
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.75s ease-out, visibility 0.75s ease-out;
            opacity: 1;
            visibility: visible;
        }

        #preloader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .preloader-logo {
            width: 20%;
            height: auto;
            animation: pulse 2s infinite ease-in-out;
        }

        .scrolling-text-container {
            height: 40px;
            overflow: hidden;
            margin-top: 1.5rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .scrolling-text-container ul {
            list-style: none;
            padding: 0;
            margin: 0;
            animation: scrollText 5s forwards cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }

        .scrolling-text-container li {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            line-height: 40px;
            text-align: center;
        }

        .scrolling-text-container li.final-text {
            color: #a0deff;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes scrollText {
            0% {
                transform: translateY(0);
            }

            10% {
                transform: translateY(0);
            }

            25% {
                transform: translateY(-40px);
            }

            35% {
                transform: translateY(-40px);
            }

            50% {
                transform: translateY(-80px);
            }

            60% {
                transform: translateY(-80px);
            }

            75% {
                transform: translateY(-120px);
            }

            100% {
                transform: translateY(-120px);
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 0.8s ease-out, transform 0.6s ease-out;
        }

        .animate-on-scroll.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .animation-delay-100 {
            transition-delay: 100ms;
        }

        .animation-delay-200 {
            transition-delay: 200ms;
        }

        .animation-delay-300 {
            transition-delay: 300ms;
        }

        .animation-delay-400 {
            transition-delay: 400ms;
        }

        .animation-delay-500 {
            transition-delay: 500ms;
        }

        /* Style untuk kursor mengetik */
        #animated-text::after {
            content: '|';
            display: inline-block;
            animation: blink 0.7s infinite;
            color: #a0deff;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }
    </style>
</head>

<body class="antialiased bg-slate-50 text-slate-800">

    <div id="preloader">
        <div class="preloader-content text-center">
            <img src="{{ asset('images/icon-web.png') }}" alt="SISINPEM Logo" class="preloader-logo inline-block">
            <div class="scrolling-text-container">
                <ul>
                    <li>MENCATAT ASET</li>
                    <li>MEMANTAU KONDISI</li>
                    <li>MELAPORKAN KERUSAKAN</li>
                    <li class="final-text">SISINPEM</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="min-h-screen flex flex-col">
        <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center space-x-2">
                            <img class="h-16 w-auto" src="{{ asset('images/icon-web.png') }}" alt="SISINPEM Logo">
                        </a>
                    </div>
                    <div class="flex items-center">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="text-sm font-medium text-white hover:text-blue-200 transition-colors">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-sm font-semibold text-white bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-all duration-300 ease-in-out">Login</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <header class="hero-gradient text-white relative flex items-center min-h-screen overflow-hidden">
            <div class="hero-bg-shape"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-white/5 rounded-full filter blur-3xl"></div>
            <div class="absolute top-20 -left-40 w-96 h-96 bg-white/5 rounded-full filter blur-3xl"></div>

            <div class="container mx-auto px-6 relative z-10 py-20">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="text-center md:text-left">
                        <h1 id="hero-title"
                            class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 animate-fade-in-up"
                            style="text-shadow: 0px 2px 4px rgba(0,0,0,0.2);">
                            Kelola <span id="animated-text"></span> Jurusan Jadi Lebih Mudah
                        </h1>
                        <p
                            class="text-lg sm:text-xl text-blue-100 mb-10 max-w-xl mx-auto md:mx-0 animation-delay-200 animate-fade-in-up">
                            SISINPEM adalah platform terintegrasi untuk civitas akademika Manajemen Informatika - 
                            Polsri dalam mencatat, memantau, dan melaporkan aset pembelajaran.
                        </p>
                        <div class="animation-delay-400 animate-fade-in-up mb-10">
                            @guest
                                <a href="{{ route('login') }}"
                                    class="bg-white text-blue-700 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-blue-50 transform hover:scale-105 transition-all duration-300 ease-in-out text-lg">
                                    Mulai Sekarang &rarr;
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}"
                                    class="bg-white text-blue-700 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-blue-50 transform hover:scale-105 transition-all duration-300 ease-in-out text-lg">
                                    Masuk ke Dashboard
                                </a>
                            @endguest
                        </div>
                        <div class="animation-delay-500 animate-fade-in-up">
                            <div class="flex items-center justify-center md:justify-start space-x-6">
                                <img src="{{ asset('images/logo_polsri.png') }}"
                                    alt="Logo Politeknik Negeri Sriwijaya" class="h-16 bg-transparent p-1 rounded-md">
                                <img src="{{ asset('images/logo_mi.png') }}"
                                    alt="Logo Manajemen Informatika" class="h-16 bg-transparent p-1 rounded-md">
                                {{-- <span class="text-white font-semibold">Manajemen Informatika</span> --}}
                            </div>
                        </div>
                    </div>
                    <div class="animation-delay-200 animate-fade-in-up">
                        <img src="{{ asset('images/Hero-1.jpeg') }}" alt="Laboratorium Manajemen Informatika Polsri"
                            class="rounded-xl shadow-2xl transform hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            </div>
        </header>

        <section id="fitur" class="py-24 bg-white min-h-screen flex flex-col justify-center">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
                    <div class="animate-on-scroll">
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                            Solusi Terpusat Untuk Aset Jurusan
                        </h2>
                        <p class="text-lg text-slate-600 mb-4">
                            Lupakan pencatatan manual yang rentan hilang dan sulit dilacak. SISINPEM menyediakan semua
                            yang dibutuhkan untuk manajemen aset perkuliahan yang modern.
                        </p>
                        <p class="text-slate-600">
                            Dengan SISINPEM, setiap item di laboratorium, dari komputer hingga proyektor, dapat dilacak
                            dengan mudah. Mahasiswa bisa langsung melaporkan jika ada kerusakan, dan admin dapat
                            memantau proses perbaikan hingga tuntas.
                        </p>
                    </div>
                    <div class="animate-on-scroll animation-delay-200">
                        <img src="{{ asset('screenshot/ss_dashboard.png') }}"
                            class="rounded-lg shadow-xl" alt="Ilustrasi Dashboard SISINPEM">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                    <div class="animate-on-scroll animation-delay-200">
                        <div
                            class="bg-slate-50 p-8 rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-2 border-t-4 border-blue-500 transition-all duration-300 text-center h-full">
                            <div class="text-blue-600 mb-5 inline-block bg-blue-100 p-4 rounded-full"><svg
                                    class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                </svg></div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3">Pencatatan Terpusat</h3>
                            <p class="text-slate-600">Catat seluruh data inventaris dengan detail dan akurat dalam satu
                                platform.</p>
                        </div>
                    </div>
                    <div class="animate-on-scroll animation-delay-300">
                        <div
                            class="bg-slate-50 p-8 rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-2 border-t-4 border-amber-500 transition-all duration-300 text-center h-full">
                            <div class="text-amber-600 mb-5 inline-block bg-amber-100 p-4 rounded-full"><svg
                                    class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z">
                                    </path>
                                </svg></div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3">Pelaporan Kerusakan Cepat</h3>
                            <p class="text-slate-600">Mahasiswa dan staf dapat langsung melaporkan kerusakan aset untuk
                                perbaikan cepat.</p>
                        </div>
                    </div>
                    <div class="animate-on-scroll animation-delay-400">
                        <div
                            class="bg-slate-50 p-8 rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-2 border-t-4 border-green-500 transition-all duration-300 text-center h-full">
                            <div class="text-green-600 mb-5 inline-block bg-green-100 p-4 rounded-full"><svg
                                    class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z">
                                    </path>
                                </svg></div>
                            <h3 class="text-xl font-bold text-slate-800 mb-3">Monitoring Real-Time</h3>
                            <p class="text-slate-600">Pantau kondisi, status, dan riwayat setiap aset melalui dashboard
                                yang intuitif.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-100 p-8 rounded-lg max-w-3xl mx-auto text-center animate-on-scroll">
                    <img src="https://placehold.co/80x80/cbd5e0/4a5568?text=Foto+Dosen"
                        class="w-20 h-20 rounded-full mx-auto mb-4" alt="Foto Dosen MI">
                    <p class="text-slate-600 italic text-lg mb-4">"SISINPEM sangat membantu kami dalam mendata aset di
                        laboratorium. Proses pelaporan kerusakan dari mahasiswa saat praktikum menjadi jauh lebih cepat
                        dan terdokumentasi dengan baik."</p>
                    <p class="font-bold text-slate-800">Ibu Dr. Fitriani, S.Kom., M.Kom.</p>
                    <p class="text-sm text-slate-500">Ketua Jurusan Manajemen Informatika</p>
                </div>
            </div>
        </section>

        <section class="bg-blue-600 text-white min-h-screen flex flex-col justify-center">
            <div class="container mx-auto px-6 py-16 text-center">
                <div class="mb-16">
                    <h3 class="text-3xl font-bold mb-2 animate-on-scroll">Mulai dalam 3 Langkah Mudah</h3>
                    <p class="text-blue-200 max-w-xl mx-auto mb-10 animate-on-scroll animation-delay-100">Prosesnya
                        cepat dan sederhana untuk membawa manajemen aset Jurusan ke level berikutnya.</p>
                    <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                        <div class="animate-on-scroll animation-delay-200">
                            <div class="bg-white/10 p-6 rounded-lg h-full">
                                <div
                                    class="bg-white text-blue-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-2xl">
                                    1</div>
                                <h4 class="font-semibold mb-2">Daftar & Atur</h4>
                                <p class="text-sm text-blue-200">Buat akun untuk dosen, staf, dan mahasiswa. Atur
                                    kategori aset sesuai laboratorium.</p>
                            </div>
                        </div>
                        <div class="animate-on-scroll animation-delay-300">
                            <div class="bg-white/10 p-6 rounded-lg h-full">
                                <div
                                    class="bg-white text-blue-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-2xl">
                                    2</div>
                                <h4 class="font-semibold mb-2">Catat Semua Aset</h4>
                                <p class="text-sm text-blue-200">Mulai masukkan data inventaris jurusan ke dalam sistem
                                    dengan detail lengkap.</p>
                            </div>
                        </div>
                        <div class="animate-on-scroll animation-delay-400">
                            <div class="bg-white/10 p-6 rounded-lg h-full">
                                <div
                                    class="bg-white text-blue-600 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-2xl">
                                    3</div>
                                <h4 class="font-semibold mb-2">Pantau & Laporkan</h4>
                                <p class="text-sm text-blue-200">Gunakan dashboard untuk memantau kondisi dan kelola
                                    laporan kerusakan dari mahasiswa.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold mb-4 animate-on-scroll">Siap Memodernisasi Manajemen Aset
                    Jurusan MI?</h2>
                <p class="text-blue-200 mb-8 max-w-xl mx-auto animate-on-scroll animation-delay-100">Bergabunglah
                    sekarang dan rasakan kemudahan manajemen aset digital di lingkungan kampus.</p>
                <a href="{{ route('login') }}"
                    class="bg-white text-blue-700 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-blue-50 transform hover:scale-105 transition-all duration-300 ease-in-out text-lg animate-on-scroll animation-delay-200">
                    Login dan Mulai
                </a>
            </div>
        </section>

        <footer class="bg-slate-800 text-slate-300">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-2 mb-2"><img class="h-8 w-auto"
                                src="{{ asset('images/icon-web.png') }}" alt="SISINPEM Logo">
                            <h3 class="text-lg font-semibold text-white">SISINPEM POLSRI</h3>
                        </div>
                        <p class="text-sm text-slate-400">Sistem Informasi Inventaris Pembelajaran untuk Jurusan
                            Manajemen Informatika, Politeknik Negeri Sriwijaya.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-200 tracking-wider uppercase">Tautan</h4>
                        <ul class="mt-4 space-y-2">
                            <li><a href="#" class="hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="#fitur" class="hover:text-white transition-colors">Fitur</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-200 tracking-wider uppercase">Legal</h4>
                        <ul class="mt-4 space-y-2">
                            <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                            </li>
                            <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 border-t border-slate-700 pt-8 text-center text-sm text-slate-400">
                    <p>&copy; {{ date('Y') }} SISINPEM | Dibuat oleh Kelompok 2 MIC Angkatan 2023.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Script untuk Navbar
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-slate-900', 'shadow-lg');
            } else {
                navbar.classList.remove('bg-slate-900', 'shadow-lg');
            }
        });

        // Script untuk Preloader
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            setTimeout(() => {
                preloader.classList.add('hidden');
            }, 5000); // Durasi disesuaikan dengan total durasi animasi CSS
        });

        // Script untuk Animasi saat Scroll
        document.addEventListener("DOMContentLoaded", () => {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });
            animatedElements.forEach(el => {
                observer.observe(el);
            });
        });

        // =======================================================
        // Script Animasi Ketik Berulang
        // =======================================================
        document.addEventListener('DOMContentLoaded', function() {
            const animatedText = document.getElementById('animated-text');
            // Ganti daftar kata ini sesuai keinginan Anda
            const wordsToAnimate = ["Inventaris", "Aset", "Data"];

            let wordIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            const typingSpeed = 150;
            const erasingSpeed = 75;
            const delayBetweenWords = 2000;

            function type() {
                const currentWord = wordsToAnimate[wordIndex];
                const text = currentWord.substring(0, charIndex);
                animatedText.textContent = text;

                if (!isDeleting && charIndex < currentWord.length) {
                    // Mengetik
                    charIndex++;
                    setTimeout(type, typingSpeed);
                } else if (isDeleting && charIndex > 0) {
                    // Menghapus
                    charIndex--;
                    setTimeout(type, erasingSpeed);
                } else {
                    // Beralih antara mengetik dan menghapus
                    isDeleting = !isDeleting;
                    if (!isDeleting) {
                        wordIndex = (wordIndex + 1) % wordsToAnimate.length;
                    }
                    setTimeout(type, isDeleting ? delayBetweenWords : 500);
                }
            }
            type();
        });
    </script>
</body>

</html>
