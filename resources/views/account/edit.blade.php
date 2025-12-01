@section('title', 'Profil Saya - iGuppy')
<x-app-layout>
    
    <button id="toggleSidebar" class="fixed top-4 left-4 text-white text-2xl z-50 bg-gray-800 p-2 rounded-md shadow-md hover:bg-gray-700 transition">
        ☰
    </button>

    <!-- LEFT SIDEBAR -->
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
            <a href="{{ route('community.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full">
                <span class="text-2xl">💬</span>
                <span class="menu-text opacity-0 transition-opacity duration-300">Community</span>
            </a>
            @if(auth()->user()->name !== 'Admin')
            <a href="{{ route('account.edit') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full active bg-gray-700 font-bold">
                <span class="text-2xl">👤</span>
                <span class="menu-text opacity-0 transition-opacity duration-300">Akun</span>
            </a>
            @endif
            @if(auth()->user()->name === 'Admin')
                <a href="{{ route('users.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full">
                    <span class="text-2xl">👥</span>
                    <span class="menu-text opacity-0 transition-opacity duration-300">Users</span>
                </a>
            @endif
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div id="mainContent" class="p-6 space-y-6 ml-20 transition-all duration-300 ease-in-out">
        <h1 class="text-3xl font-bold">Akun Saya</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded">{{ session('success') }}</div>
        @endif

        <!-- Profile Preview -->
        <div class="bg-white rounded-xl shadow p-6 flex items-center space-x-6">
            <img id="avatarPreview" src="{{ $user->avatar ? asset('avatar/'.$user->avatar) : asset('avatar/default.png') }}" class="w-24 h-24 rounded-full object-cover border" alt="Avatar">
            <div class="flex-1">
                <p class="text-lg font-semibold">{{ $user->name }}</p>
                <p class="text-gray-500">{{ $user->email }}</p>
            </div>
            <button id="editBtn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Atur Profil</button>
        </div>

        <!-- Modal Edit Profil -->
        <div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
            <div class="bg-white rounded-xl shadow-xl p-6 w-96 transform scale-95 transition-all duration-300">
                <h2 class="text-xl font-bold mb-4">Edit Profil</h2>

                <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Avatar</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Nama</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Password Baru</label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2" placeholder="Kosongkan jika tidak ingin ganti">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" placeholder="Kosongkan jika tidak ingin ganti">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" id="closeModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebarTitle = document.getElementById('sidebarTitle');
        const menuTexts = document.querySelectorAll('.menu-text');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', () => {
            const collapsed = sidebar.classList.contains('w-20');
            if(collapsed){
                sidebar.classList.remove('w-20'); sidebar.classList.add('w-60');
                sidebarTitle.style.opacity='1';
                menuTexts.forEach(t=>t.style.opacity='1');
                mainContent.style.marginLeft='15rem';
            }else{
                sidebar.classList.remove('w-60'); sidebar.classList.add('w-20');
                sidebarTitle.style.opacity='0';
                menuTexts.forEach(t=>t.style.opacity='0');
                mainContent.style.marginLeft='5rem';
            }
        });

        // Modal edit profil
        const editBtn = document.getElementById('editBtn');
        const editModal = document.getElementById('editModal');
        const closeModal = document.getElementById('closeModal');
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');

        editBtn.addEventListener('click', () => {
            editModal.classList.remove('opacity-0','pointer-events-none');
            setTimeout(()=>{
                editModal.classList.add('opacity-100');
                editModal.querySelector('div').classList.remove('scale-95');
                editModal.querySelector('div').classList.add('scale-100');
            },10);
        });

        closeModal.addEventListener('click', () => {
            editModal.querySelector('div').classList.remove('scale-100');
            editModal.querySelector('div').classList.add('scale-95');
            editModal.classList.remove('opacity-100');
            setTimeout(()=>{ editModal.classList.add('opacity-0','pointer-events-none'); },300);
        });

        // Preview avatar sebelum submit
        avatarInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if(file){
                avatarPreview.src = URL.createObjectURL(file);
            }
        });
    </script>

    <style>
        @keyframes slide-in{from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}
        @keyframes slide-out{from{transform:translateX(0);opacity:1;}to{transform:translateX(100%);opacity:0;}}
        .animate-slide-in{animation:slide-in 0.5s forwards;}
        .animate-slide-out{animation:slide-out 0.5s forwards;}
        aside nav a.active{background-color:#1f2937;font-weight:bold;}
    </style>
</x-app-layout>
