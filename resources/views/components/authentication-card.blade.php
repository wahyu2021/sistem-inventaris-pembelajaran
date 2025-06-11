{{-- [MODIFIKASI] Kartu dibuat semi-transparan dengan efek blur, bayangan, dan sudut yang lebih modern --}}
<div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/90 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl">
    {{-- Logo ditempatkan di dalam kartu --}}
    <div>
        {{ $logo }}
    </div>

    <div class="mt-6">
        {{ $slot }}
    </div>
</div>
