<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

                <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2 sm:mb-0">
                        Daftar Pengguna
                    </h3>
                    <button wire:click="openCreateModal()"
                        class="px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-opacity-50 transition ease-in-out duration-150">
                        <x-heroicon-o-plus class="h-5 w-5 inline-block -mt-1 mr-1" />
                        Tambah Pengguna Baru
                    </button>
                </div>

                {{-- Flash Messages --}}
                @if (session('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4 shadow"
                        role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Modal --}}
                <x-dialog-modal wire:model.live="isModalOpen">
                    <x-slot name="title">
                        {{ $form->userId ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}
                    </x-slot>

                    <x-slot name="content">
                        <div class="space-y-4">
                            <div>
                                <x-label for="name" value="{{ __('Nama Lengkap') }}" />
                                <x-input id="name" type="text" class="mt-1 block w-full"
                                    wire:model="form.name" />
                                <x-input-error for="form.name" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="email" value="{{ __('Alamat Email') }}" />
                                <x-input id="email" type="email" class="mt-1 block w-full"
                                    wire:model="form.email" />
                                <x-input-error for="form.email" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="role" value="{{ __('Peran (Role)') }}" />
                                <select wire:model="form.role" id="role"
                                    class="mt-1 block w-full form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach ($allowedRoles as $roleOption)
                                        <option value="{{ $roleOption }}">{{ Str::title($roleOption) }}</option>
                                    @endforeach
                                </select>
                                <x-input-error for="form.role" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="password">
                                    {{ __('Password') }}
                                    @if ($form->userId)
                                        <span class="text-xs text-gray-500">(Opsional - Isi jika ingin mengubah)</span>
                                    @endif
                                </x-label>
                                <x-input id="password" type="password" class="mt-1 block w-full"
                                    wire:model="form.password" autocomplete="new-password" />
                                <x-input-error for="form.password" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="password_confirmation" value="{{ __('Konfirmasi Password') }}" />
                                <x-input id="password_confirmation" type="password" class="mt-1 block w-full"
                                    wire:model="form.password_confirmation" autocomplete="new-password" />
                                <x-input-error for="form.password_confirmation" class="mt-2" />
                            </div>
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                        <x-secondary-button wire:click="$set('isModalOpen', false)" wire:loading.attr="disabled">
                            {{ __('Batal') }}
                        </x-secondary-button>

                        <x-button class="ms-3" wire:click="save" wire:loading.attr="disabled">
                            {{ __('Simpan') }}
                        </x-button>
                    </x-slot>
                </x-dialog-modal>

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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if ($user->role == 'admin') bg-red-100 text-red-800 @else bg-green-100 text-green-800 @endif">
                                            {{ Str::title($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="openEditModal({{ $user->id }})"
                                            class="text-indigo-600 hover:text-indigo-800">
                                            Edit
                                        </button>
                                        @if (Auth::id() !== $user->id)
                                            <button wire:click="confirmUserDeletion({{ $user->id }})"
                                                class="ml-3 text-red-600 hover:text-red-800">
                                                Hapus
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada pengguna ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                @if ($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- modal untuk delete --}}
    <x-confirmation-modal wire:model.live="confirmingUserDeletion">
        <x-slot name="title">
            Hapus Pengguna
        </x-slot>

        <x-slot name="content">
            Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                Batal
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                Hapus Pengguna
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
