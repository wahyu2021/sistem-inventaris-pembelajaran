<div>
    {{-- Slot Header untuk x-app-layout --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    {{-- Konten Utama Komponen --}}
    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2 sm:mb-0">
                        Daftar Pengguna
                    </h3>
                    <button wire:click="create()"
                        class="px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-opacity-50 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H5a1 1 0 110-2h4V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Pengguna Baru
                    </button>
                </div>

                {{-- Pesan Flash Sukses --}}
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif
                {{-- Pesan Flash Error (jika ada, seperti gagal delete diri sendiri) --}}
                @if (session()->has('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif


                {{-- Modal Tambah/Edit --}}
                @if ($isOpen)
                    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                wire:click="closeModal()" aria-hidden="true"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                role="document">
                                <form wire:submit.prevent="store">
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                            {{ $userId ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}
                                        </h3>
                                        <div class="space-y-4">
                                            <div>
                                                <label for="name"
                                                    class="block text-sm font-medium text-gray-700">Nama Lengkap
                                                    <span class="text-red-500">*</span></label>
                                                <input type="text" wire:model.defer="name" id="name"
                                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                @error('name')
                                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="email"
                                                    class="block text-sm font-medium text-gray-700">Alamat Email
                                                    <span class="text-red-500">*</span></label>
                                                <input type="email" wire:model.defer="email" id="email"
                                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('email') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                @error('email')
                                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="role"
                                                    class="block text-sm font-medium text-gray-700">Peran (Role)
                                                    <span class="text-red-500">*</span></label>
                                                <select wire:model.defer="role" id="role"
                                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('role') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                    @foreach ($allowedRoles as $roleOption)
                                                        <option value="{{ $roleOption }}">
                                                            {{ Str::title($roleOption) }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role')
                                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="password"
                                                    class="block text-sm font-medium text-gray-700">Password
                                                    @if (!$userId)
                                                        <span class="text-red-500">*</span>
                                                    @else
                                                        (Opsional - Isi jika ingin mengubah)
                                                    @endif
                                                </label>
                                                <input type="password" wire:model.defer="password" id="password"
                                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('password') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                @error('password')
                                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label for="password_confirmation"
                                                    class="block text-sm font-medium text-gray-700">Konfirmasi Password
                                                    @if (!$userId || $password)
                                                        <span class="text-red-500">*</span>
                                                    @endif
                                                </label>
                                                <input type="password" wire:model.defer="password_confirmation"
                                                    id="password_confirmation"
                                                    class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('password_confirmation') border-red-500 @enderror focus:ring-blue-500 focus:border-blue-500">
                                                @error('password_confirmation')
                                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-700 text-base font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                                        <button type="button" wire:click="closeModal()"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Filter dan Pencarian --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..."
                        class="col-span-1 md:col-span-3 form-input rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <select wire:model.live="filterRole"
                        class="form-select rounded-md shadow-sm py-2 px-3 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Peran</option>
                        @foreach ($allowedRoles as $roleOption)
                            <option value="{{ $roleOption }}">{{ Str::title($roleOption) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tabel Pengguna --}}
                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                {{-- Bisa ditambahkan kolom foto profil jika dikelola --}}
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Peran</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tgl Bergabung</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                        @if (Auth::id() === $user->id)
                                            <span class="text-xs text-blue-500">(Anda)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if ($user->role == 'admin') bg-red-100 text-red-800 
                                            @elseif($user->role == 'dosen') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ Str::title($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="text-indigo-600 hover:text-indigo-800 focus:outline-none transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="ml-1">Edit</span>
                                        </button>
                                        @if (Auth::id() !== $user->id)
                                            {{-- Tombol hapus hanya muncul jika bukan user itu sendiri --}}
                                            <button wire:click="delete({{ $user->id }})"
                                                wire:confirm="Anda yakin ingin menghapus pengguna '{{ $user->name }}'?"
                                                class="ml-3 text-red-600 hover:text-red-800 focus:outline-none transition ease-in-out duration-150">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="ml-1">Hapus</span>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-6">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                            <p class="mt-2 text-lg text-gray-700">Belum ada data pengguna.</p>
                                            <p class="text-xs text-gray-500">Silakan tambahkan data baru menggunakan
                                                tombol di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Link Paginasi --}}
                @if ($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
