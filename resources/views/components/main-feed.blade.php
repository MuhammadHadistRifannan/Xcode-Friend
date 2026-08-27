@props(['posts'])

<div class="w-full">
    <!-- Top Navigation Tabs -->
    <div class="bg-white rounded-t-lg border-b border-gray-200 flex">
        <button class="flex-1 py-3 text-sm font-bold text-red-600 border-b-2 border-red-600 tracking-wider">
            DINDING
        </button>
        <button class="flex-1 py-3 text-sm font-bold text-gray-500 hover:bg-gray-50 tracking-wider transition-colors">
            FOTO
        </button>
        <button class="flex-1 py-3 text-sm font-bold text-gray-500 hover:bg-gray-50 tracking-wider transition-colors">
            VIDEO
        </button>
    </div>

    <!-- Create Post Box -->
    <div class="bg-white border-b border-x border-gray-200 rounded-b-lg mb-6 p-4">
        <div class="flex gap-6 mb-3 border-b border-gray-100 pb-2">
            <button class="flex items-center gap-2 text-xs font-bold text-red-600 uppercase tracking-wider">
                <i data-lucide="align-left" class="w-4 h-4"></i> Status
            </button>
            <button class="flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-gray-800 uppercase tracking-wider">
                <i data-lucide="image" class="w-4 h-4"></i> Unggah
            </button>
            <button class="flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-gray-800 uppercase tracking-wider">
                <i data-lucide="video" class="w-4 h-4"></i> Video
            </button>
        </div>
        
        <textarea 
            placeholder="> Awaiting command sequence..."
            class="w-full text-sm font-mono text-gray-600 bg-transparent resize-none focus:outline-none mb-4 h-12"
        ></textarea>
        
        <div class="flex justify-between items-center">
            <div class="flex gap-3 text-gray-400">
                <button class="hover:text-gray-600"><i data-lucide="image" class="w-4 h-4"></i></button>
                <button class="hover:text-gray-600"><i data-lucide="video" class="w-4 h-4"></i></button>
                <button class="hover:text-gray-600 font-bold text-xs">&lt; &gt;</button>
            </div>
            <button class="bg-[#b91c1c] hover:bg-red-800 text-white text-xs font-bold px-6 py-2 rounded flex items-center gap-2 transition-colors">
                TRANSMIT <i data-lucide="send" class="w-3 h-3"></i>
            </button>
        </div>
    </div>

    <!-- Feed Posts -->
    <div class="space-y-4">
        @foreach($posts as $post)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <!-- Post Header -->
                <div class="p-4 flex items-start justify-between">
                    <div class="flex gap-3">
                        <img src="{{ $post['author']['avatarUrl'] }}" alt="{{ $post['author']['name'] }}" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="text-sm">
                                <span class="font-bold text-gray-900">{{ $post['author']['name'] }}</span> 
                                <span class="text-gray-600">{{ $post['action'] }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $post['time'] }}</p>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Post Content -->
                @if($post['imageUrl'])
                    <div class="w-full">
                        <img src="{{ $post['imageUrl'] }}" alt="Post content" class="w-full h-auto">
                    </div>
                @endif

                <!-- Post Actions -->
                <div class="p-3 px-4 border-b border-gray-100 flex gap-6 text-sm text-gray-500 font-medium">
                    <button class="flex items-center gap-1.5 hover:text-gray-800">
                        <i data-lucide="heart" class="w-4 h-4"></i> Suka
                    </button>
                    <button class="flex items-center gap-1.5 hover:text-gray-800">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> Komentar
                    </button>
                    <button class="flex items-center gap-1.5 hover:text-gray-800">
                        <i data-lucide="share" class="w-4 h-4"></i> Bagikan
                    </button>
                </div>

                <!-- Comments Area -->
                <div class="p-4 bg-gray-50/50 space-y-4">
                    @foreach($post['comments'] as $comment)
                        <div class="flex gap-3 text-sm">
                            <img src="{{ $comment['author']['avatarUrl'] }}" alt="{{ $comment['author']['name'] }}" class="w-8 h-8 rounded-full">
                            <div class="flex-1">
                                <div class="bg-white p-3 rounded-lg border border-gray-100">
                                    <span class="font-bold text-gray-900 mr-2">{{ $comment['author']['name'] }}</span>
                                    @if(isset($comment['author']['username']))
                                        <span class="text-gray-500 text-xs mr-2">{{ $comment['author']['username'] }}</span>
                                    @endif
                                    {{ $comment['text'] }}
                                </div>
                                <div class="flex gap-4 mt-1 text-xs text-gray-500 font-medium px-2">
                                    <span>{{ $comment['time'] }}</span>
                                    <button class="hover:text-gray-800">Suka</button>
                                    <button class="hover:text-gray-800">Balas</button>
                                </div>
                            </div>
                            @if($comment['id'] === 2) 
                                <button class="text-gray-400 h-fit hover:text-gray-600">
                                    <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach

                    <!-- Write Comment Box -->
                    <div class="flex gap-3 mt-4 items-center">
                        <img src="{{ $post['author']['avatarUrl'] }}" alt="Me" class="w-8 h-8 rounded-full">
                        <input 
                            type="text" 
                            placeholder="Tulis komentar..." 
                            class="flex-1 bg-white border border-gray-200 rounded-full px-4 py-2 text-sm focus:outline-none focus:border-gray-300"
                        >
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
