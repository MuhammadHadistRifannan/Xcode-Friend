<nav class="bg-[#0a0a0a] border-b border-gray-800 text-white flex items-center justify-between px-6 py-3">
    <div class="flex items-center gap-6">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                <span class="text-black font-bold text-xs">&gt;_</span>
            </div>
            <span class="font-bold tracking-wider text-sm">XCODE-FRIENDS</span>
        </div>
        
        <div class="hidden md:flex items-center gap-6 ml-4 text-sm text-gray-300">
            <a href="#" class="hover:text-white transition-colors">Beranda</a>
            <a href="#" class="hover:text-white transition-colors">Telusur</a>
            <a href="#" class="hover:text-white transition-colors">Video</a>
            <a href="{{ route('pages.index') }}" class="text-red-600 font-medium">Pages</a>
            <!-- Dropdown Container -->
            <div class="relative group py-2">
                <a href="#" class="hover:text-white transition-colors flex items-center gap-1 cursor-pointer">
                    My Apps <span class="text-xs">▾</span>
                </a>
                
                <!-- Dropdown Menu -->
                <div class="absolute right-0 top-full mt-2 w-56 bg-[#2b2b2b] border border-gray-700 rounded-xl shadow-lg p-2 flex flex-col gap-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                    <a href="#" class="flex items-center gap-3 p-3 bg-red-700 text-white rounded-md">
                        <i data-lucide="layout-grid" class="w-5 h-5"></i>
                        <span class="font-medium">Dasbor</span>
                    </a>
                    <a href="{{ route('foto.index') }}" class="flex items-center gap-3 p-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors">
                        <i data-lucide="image" class="w-5 h-5"></i>
                        <span class="font-medium">Foto</span>
                    </a>
                    <a href="{{ route('video.index') }}" class="flex items-center gap-3 p-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors">
                        <i data-lucide="video" class="w-5 h-5"></i>
                        <span class="font-medium">Video</span>
                    </a>
                    <a href="{{ route('undang.index') }}" class="flex items-center gap-3 p-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        <span class="font-medium">Undang</span>
                    </a>
                    <a href="{{ route('desain-profil.index') }}" class="flex items-center gap-3 p-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors">
                        <i data-lucide="user-circle" class="w-5 h-5"></i>
                        <span class="font-medium">Desain Profil</span>
                    </a>
                    <a href="{{ route('my-pages.index') }}" class="flex items-center gap-3 p-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span class="font-medium">My Pages</span>
                    </a>
                    <a href="{{ route('groups.index') }}" class="flex items-center gap-3 p-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition-colors">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span class="font-medium">Groups</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="relative hidden sm:block">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"></i>
            <input 
                type="text" 
                placeholder="Search Network" 
                class="bg-[#1a1a1a] text-sm text-white rounded-full pl-10 pr-4 py-1.5 focus:outline-none focus:ring-1 focus:ring-gray-600 w-48 lg:w-64"
            >
        </div>
        
        <div class="flex items-center gap-4 text-gray-300">
            <!-- TODO: REMOVE THIS BUTTON ONCE AUTH IS DONE -->
            <a href="{{ route('admin.login') }}" class="bg-red-600 hover:bg-red-700 text-white text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 transition-colors shadow-sm">
                <i data-lucide="shield" class="w-3 h-3"></i> ADMIN
            </a>
            
            <button class="hover:text-white"><i data-lucide="user" class="w-5 h-5"></i></button>
            <button class="hover:text-white"><i data-lucide="bell" class="w-5 h-5"></i></button>
            <button class="hover:text-white"><i data-lucide="message-square" class="w-5 h-5"></i></button>
            <div class="w-7 h-7 bg-red-600 rounded-full flex items-center justify-center text-white text-xs cursor-pointer">
                <i data-lucide="user" class="w-4 h-4"></i>
            </div>
        </div>
        <button class="md:hidden text-gray-300"><i data-lucide="menu" class="w-6 h-6"></i></button>
    </div>
</nav>
