<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pemberitahuan Anda') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700">Kotak Masuk Notifikasi</h3>
                    @if (Auth::user()->unreadNotifications->isNotEmpty())
                        <button wire:click="markAllAsRead"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            Tandai Semua Terbaca
                        </button>
                    @endif
                </div>

                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="space-y-4">
                    @forelse ($notifications as $notification)
                        <div
                            class="p-4 rounded-lg flex items-start space-x-4 {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50 border-l-4 border-blue-500' }}">
                            <div class="flex-shrink-0">
                                @if ($notification->data['type'] === 'damage_report_status_updated')
                                    <x-heroicon-o-check-circle class="h-6 w-6 text-green-500" />
                                @endif
                            </div>
                            <div class="flex-grow">
                                <p class="text-sm text-gray-800">{{ $notification->data['message'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 flex items-center space-x-2">
                                @if (!$notification->read_at)
                                    <button wire:click="markAsRead('{{ $notification->id }}')"
                                        class="text-xs text-blue-600 hover:underline" title="Tandai sudah dibaca">
                                        Tandai Dibaca
                                    </button>
                                @endif
                                <button wire:click="deleteNotification('{{ $notification->id }}')"
                                    class="text-red-500 hover:text-red-700" title="Hapus notifikasi">
                                    <x-heroicon-o-x-mark class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <x-heroicon-o-bell-slash class="h-12 w-12 mx-auto text-gray-400" />
                            <p class="mt-4">Tidak ada pemberitahuan untuk Anda saat ini.</p>
                        </div>
                    @endforelse
                </div>

                @if ($notifications->hasPages())
                    <div class="mt-6">
                        {{ $notifications->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
