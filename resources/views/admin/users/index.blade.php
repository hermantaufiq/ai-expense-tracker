<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Status Langganan</th>
                                    <th scope="col" class="px-6 py-3">Status Akun</th>
                                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_premium)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Premium</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Free</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_banned)
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Banned</span>
                                        @else
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right flex justify-end space-x-2">
                                        <!-- Toggle Premium Form -->
                                        <form action="{{ route('admin.users.toggle-premium', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="{{ $user->is_premium ? 'text-orange-500 hover:text-orange-700' : 'text-green-600 hover:text-green-900' }} font-medium text-sm">
                                                {{ $user->is_premium ? 'Downgrade' : 'Upgrade Premium' }}
                                            </button>
                                        </form>

                                        <!-- Toggle Ban Form -->
                                        <form action="{{ route('admin.users.toggle-ban', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="{{ $user->is_banned ? 'text-blue-600 hover:text-blue-900' : 'text-red-600 hover:text-red-900' }} font-medium text-sm ml-3">
                                                {{ $user->is_banned ? 'Unban' : 'Ban User' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
