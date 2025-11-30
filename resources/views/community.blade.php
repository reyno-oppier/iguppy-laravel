<style>
    /* Global Backdrop Style for all dialogs (clean, modern, consistent) */
    dialog::backdrop {
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
    }

    /* Custom styles for the sidebar's smooth transition */
    @keyframes slide-in{from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}
    @keyframes slide-out{from{transform:translateX(0);opacity:1;}to{transform:translateX(100%);opacity:0;}}
    .animate-slide-in{animation:slide-in 0.5s forwards;}
    .animate-slide-out{animation:slide-out 0.5s forwards;}
    aside nav a.active{background-color:#1f2937;font-weight:bold;}

    /* Ensure the toggle button is above the sidebar */
    #toggleSidebar {
        z-index: 51; 
    }
</style>

<x-app-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    
    <button id="toggleSidebar" class="fixed top-4 left-4 text-white text-2xl z-50 bg-gray-800 p-2 rounded-md shadow-md hover:bg-gray-700 transition">
        ☰
    </button>

    <aside id="sidebar" class="fixed top-0 left-0 h-full bg-gray-900 text-white shadow-xl flex flex-col items-center pt-20 w-20 transition-all duration-300 ease-in-out">
        <h1 id="sidebarTitle" class="text-2xl font-bold mb-8 overflow-hidden whitespace-nowrap opacity-0 transition-opacity duration-300 text-center w-full">iGuppy</h1>
        <nav class="space-y-4 flex flex-col items-center w-full">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full">
                <span class="text-2xl">🏠</span>
                <span class="menu-text opacity-0 transition-opacity duration-300">Dashboard</span>
            </a>
            <a href="{{ route('feeder.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full">
                <span class="text-2xl">🍽️</span>
                <span class="menu-text opacity-0 transition-opacity duration-300">Feeder</span>
            </a>
            {{-- Setting the current link as 'active' for Community Feed --}}
            <a href="#" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full active bg-gray-700 font-bold">
                <span class="text-2xl">💬</span>
                <span class="menu-text opacity-0 transition-opacity duration-300">Catatan</span>
            </a>
            <a href="{{ route('account.edit') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full">
                <span class="text-2xl">👤</span>
                <span class="menu-text opacity-0 transition-opacity duration-300">Akun</span>
            </a>
        </nav>
    </aside>

    {{-- Main content starts with a left margin of 5rem (ml-20) to accommodate the collapsed sidebar (w-20) --}}
    <div id="mainContent" class="ml-20 transition-all duration-300 ease-in-out">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="mb-16 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <div>
                    <h1 class="text-5xl font-extrabold text-gray-900 tracking-tighter leading-tight">
                        <span class="text-indigo-600">Catatan</span> iguppy
                    </h1>
                    <p class="text-gray-500 mt-3 text-lg">
                        Fitur Tambah catatan agar pengguna lain dapat mengetahui informasi terbaru!
                    </p>
                </div>

                {{-- **Static Add Note Button** --}}
                <button onclick="document.getElementById('addNoteModal').showModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold
                           px-6 py-3 rounded-full shadow-xl 
                           transition transform hover:scale-105
                           mt-6 sm:mt-0 text-base"> 
                    + Buat Catatan
                </button>
            </div>
            <h2 class="text-3xl font-bold mb-10 text-gray-800 border-b pb-3 border-gray-200">
                ✨ Catatan Terbaru
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
                @foreach ($recent as $post)
                    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100
                                hover:shadow-xl hover:shadow-indigo-100 transition duration-300 flex flex-col">
                        
                        {{-- User Info (Consistent size) --}}
                        <div class="flex items-center mb-5">
                            <img src="{{ $post->user->avatar ? asset('avatar/'.$post->user->avatar) : asset('avatar/default.png') }}"
                                 class="w-10 h-10 mr-4 rounded-full object-cover shadow-sm border border-gray-100">
                            <div>
                                <div class="font-semibold text-base text-gray-900">{{ $post->user->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        
                        {{-- Note Content (Consistent typography) --}}
                        <div class="text-gray-700 leading-relaxed text-base flex-1 mb-6">
                            {{ $post->content }}
                        </div>

                        {{-- Actions Footer (Consistent alignment) --}}
                        <div class="flex justify-end pt-4 border-t border-gray-100 mt-auto">
                            {{-- Reaction Button --}}
                            <form action="{{ route('community.react', $post->id) }}" method="POST">
                                @csrf
                                <button class="text-gray-500 hover:text-indigo-600 transition font-medium flex items-center space-x-1 p-2 rounded-lg hover:bg-indigo-50"
                                        type="submit">
                                    <i class="far fa-thumbs-up text-lg"></i>
                                    <span class="text-sm">{{ $post->reactions->count() ?? 0 }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <h2 class="text-3xl font-bold mb-10 text-gray-800 border-b pb-3 border-gray-200">
                Catatan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($posts as $post)
                    <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100
                                hover:shadow-xl hover:shadow-indigo-100 transition duration-300 flex flex-col">
                        
                        {{-- User Info --}}
                        <div class="flex items-center mb-5">
                            <img src="{{ $post->user->avatar ? asset('avatar/'.$post->user->avatar) : asset('avatar/default.png') }}"
                                 class="w-10 h-10 mr-4 rounded-full object-cover shadow-sm border border-gray-100">
                            <div>
                                <div class="font-semibold text-base text-gray-900">{{ $post->user->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $post->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        {{-- Note Content --}}
                        <div class="text-gray-700 leading-relaxed text-base flex-1 mb-6">
                            {{ $post->content }}
                        </div>

                        {{-- Actions Footer (Edit/Delete/Reaction) --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-auto">

                            {{-- Reaction Button --}}
                            <form action="{{ route('community.react', $post->id) }}" method="POST">
                                @csrf
                                <button class="text-gray-500 hover:text-indigo-600 transition font-medium flex items-center space-x-1 p-2 rounded-lg hover:bg-indigo-50"
                                        type="submit">
                                    <i class="far fa-thumbs-up text-lg"></i>
                                    <span class="text-sm">{{ $post->reactions->count() ?? 0 }}</span>
                                </button>
                            </form>

                            {{-- Edit/Delete only for owner (Consistent button size) --}}
                            @if(auth()->id() === $post->user_id)
                                <div class="flex space-x-2">
                                    {{-- Edit Button --}}
                                    <button onclick="openEditModal({{ $post->id }}, '{{ addslashes($post->content) }}')"
                                        class="w-8 h-8 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center transition"
                                        title="Edit Note">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>

                                    {{-- Delete Form --}}
                                    <form action="{{ route('community.destroy', $post->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this note?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center transition"
                                            title="Delete Note">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        {{-- The modals remain outside the max-width container but inside the mainContent wrapper --}}

        <dialog id="addNoteModal"
                class="rounded-xl shadow-2xl p-0 min-w-[90%] sm:min-w-[450px] lg:min-w-[550px]">
            <form method="POST" action="{{ route('community.store') }}"
                  class="p-8 bg-white rounded-xl flex flex-col space-y-6">
                @csrf
                <h3 class="text-2xl font-bold text-gray-900">Buat Catatan 📝</h3>
                <textarea name="content" rows="6" required
                          class="w-full border-2 border-gray-300 rounded-lg p-4 text-base focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                          placeholder="Ketik catatan anda disini..."></textarea>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('addNoteModal').close()"
                            class="px-5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md shadow-blue-500/30 transition">
                        Post Note
                    </button>
                </div>
            </form>
        </dialog>

        <dialog id="editNoteModal" class="rounded-xl shadow-2xl p-0 min-w-[90%] sm:min-w-[450px] lg:min-w-[550px]">
            <form id="editNoteForm" method="POST" class="p-8 bg-white rounded-xl flex flex-col space-y-6">
                @csrf
                @method('PUT')
                <h3 class="text-2xl font-bold mb-3 text-gray-800">Edit Note ✏️</h3>
                <textarea id="editNoteContent" name="content" rows="6" required
                          class="w-full border-2 border-gray-300 rounded-lg p-4 text-base focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"></textarea>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                        onclick="document.getElementById('editNoteModal').close()"
                        class="px-5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium transition">
                        Cancel
                    </button>
                    <button class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md shadow-blue-500/30 transition">
                        Update Note
                    </button>
                </div>
            </form>
        </dialog>

    </div> {{-- end of mainContent --}}

    <script>
        // --- Sidebar logic ---
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebarTitle = document.getElementById('sidebarTitle');
        const menuTexts = document.querySelectorAll('.menu-text');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', () => {
            const collapsed = sidebar.classList.contains('w-20');
            if(collapsed){
                // Expand
                sidebar.classList.remove('w-20'); sidebar.classList.add('w-60');
                sidebarTitle.style.opacity='1';
                menuTexts.forEach(t=>t.style.opacity='1');
                mainContent.style.marginLeft='15rem'; // 15rem = w-60
            }else{
                // Collapse
                sidebar.classList.remove('w-60'); sidebar.classList.add('w-20');
                sidebarTitle.style.opacity='0';
                menuTexts.forEach(t=>t.style.opacity='0');
                mainContent.style.marginLeft='5rem'; // 5rem = w-20
            }
        });

        // --- Community/Note Modal logic ---
        function openEditModal(postId, content) {
            const modal = document.getElementById('editNoteModal');
            const textarea = document.getElementById('editNoteContent');
            const form = document.getElementById('editNoteForm');

            // Set content, handling escaped quotes from PHP
            textarea.value = content.replace(/\\'/g, "'").replace(/\\"/g, '"');
            
            // Set the dynamic form action
            form.action = `/community/${postId}`;
            modal.showModal();
        }

    </script>

</x-app-layout>