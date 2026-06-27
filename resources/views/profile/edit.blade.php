<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                Pengaturan Profil
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar Navigation -->
                <div class="w-full md:w-1/4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 sticky top-6">
                        <div class="flex flex-col items-center p-4 border-b border-gray-100 dark:border-gray-700 mb-4">
                            <div class="relative group cursor-pointer mb-3">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-blue-50 dark:border-blue-900 shadow-md">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-3xl font-bold">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white text-center">{{ auth()->user()->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">{{ auth()->user()->email }}</p>
                            @if(auth()->user()->is_premium)
                                <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM9.5 4a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm-1 4h2v5h-2V8z" clip-rule="evenodd"></path></svg>
                                    Premium Member
                                </span>
                            @else
                                <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    Free Member
                                </span>
                            @endif
                        </div>
                        
                        <nav class="space-y-1">
                            <a href="#profile-info" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Data Pribadi
                            </a>
                            <a href="#security" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/50 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Keamanan & Password
                            </a>
                            <a href="#danger" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus Akun
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="w-full md:w-3/4 space-y-6">
                    <!-- Profile Information -->
                    <div id="profile-info" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-8">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <!-- Update Password -->
                    <div id="security" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div class="p-8">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Delete User -->
                    <div id="danger" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl border border-red-100 dark:border-red-900/30">
                        <div class="p-8">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <script>
        // Simple script to handle avatar click and trigger file input in the form
        document.querySelector('.group.cursor-pointer').addEventListener('click', function() {
            const fileInput = document.getElementById('avatar_upload');
            if(fileInput) fileInput.click();
        });
    </script>
</x-app-layout>
