@section('title', 'Atur Pengguna - iGuppy | Sistem Monitoring Akuarium')
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
            <a href="{{ route('users.index') }}" id="adminMenuButton" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full">
                <span class="text-2xl">👥</span>
                <span class="menu-text opacity-0 transition-opacity duration-300">Users</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div id="mainContent" class="p-6 space-y-6 ml-20 transition-all duration-300 ease-in-out">
        <h1 class="text-3xl font-bold mb-4">Admin User Manager</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <button 
                                    class="editBtn px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                >Edit</button>

                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 w-96 transform scale-95 transition-all duration-300">
            <h2 class="text-xl font-bold mb-4">Edit User</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Name</label>
                    <input type="text" name="name" id="editName" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-semibold">Email</label>
                    <input type="email" name="email" id="editEmail" class="w-full border rounded px-3 py-2">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeModal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">Save</button>
                </div>
            </form>
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

        // Hide Users menu button if logged-in user is not "Admin"
        const adminMenuButton = document.getElementById('adminMenuButton');
        @if(auth()->user()->name !== 'Admin')
            adminMenuButton.style.display = 'none';
        @endif

        // Modal logic (same as profile modal)
        const editUrlTemplate = "{{ route('users.update', ':id') }}"; // Blade generates the base route
        const editBtns = document.querySelectorAll('.editBtn');
        const editForm = document.getElementById('editForm');
        const editName = document.getElementById('editName');
        const editEmail = document.getElementById('editEmail');

        editBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                editForm.action = editUrlTemplate.replace(':id', id); // set correct route
                editName.value = btn.dataset.name;
                editEmail.value = btn.dataset.email;

                editModal.classList.remove('opacity-0','pointer-events-none');
                setTimeout(()=>{
                    editModal.classList.add('opacity-100');
                    editModal.querySelector('div').classList.remove('scale-95');
                    editModal.querySelector('div').classList.add('scale-100');
                },10);
            });
        });

        closeModal.addEventListener('click', () => {
            editModal.querySelector('div').classList.remove('scale-100');
            editModal.querySelector('div').classList.add('scale-95');
            editModal.classList.remove('opacity-100');
            setTimeout(()=>{ editModal.classList.add('opacity-0','pointer-events-none'); },300);
        });
    </script>

    <style>
        aside nav a.active{background-color:#1f2937;font-weight:bold;}
    </style>
</x-app-layout>
