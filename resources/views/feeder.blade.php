@section('title', 'Feeder - iGuppy | Sistem Monitoring Akuarium')
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
    
    /* Style for the Firebase Modals, converting them to dialog elements for better accessibility */
    .modal-dialog {
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
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
            {{-- Feeder is the active page --}}
            <a href="{{ route('feeder.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full active bg-gray-700 font-bold">
                <span class="text-2xl">🍽️</span>
                <span class="menu-text opacity-0 transition-opacity duration-300">Feeder</span>
            </a>
            <a href="{{ route('community.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full">
                <span class="text-2xl">💬</span>
                <span class="menu-text opacity-0 transition-opacity duration-300">Community</span>
            </a>
            @if(auth()->user()->name !== 'Admin')
            <a href="{{ route('account.edit') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full">
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

    <div id="mainContent" class="ml-20 transition-all duration-300 ease-in-out">
        
        <div id="toastContainer" class="fixed top-6 right-6 space-y-2 z-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
            
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b pb-6 border-gray-200">
                <div>
                    <h1 class="text-5xl font-extrabold text-gray-900 tracking-tighter leading-tight">
                        <span class="text-green-600">Kontrol</span> pakan ⚙️
                    </h1>
                    <p class="text-gray-500 mt-3 text-lg">
                        Atur jadwal pakan otomatis atau berikan pakan segera.
                    </p>
                </div>

                <div class="flex space-x-3 mt-6 sm:mt-0">
                    <button id="openScheduleBtn" class="bg-green-600 hover:bg-green-700 text-white font-semibold
                           px-6 py-3 rounded-full shadow-lg shadow-green-500/30
                           transition transform hover:scale-105 text-base flex items-center space-x-2"> 
                        <i class="fas fa-plus-circle"></i>
                        <span>Jadwalkan Pakan</span>
                    </button>
                    <button id="feedNowBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold
                           px-6 py-3 rounded-full shadow-lg shadow-blue-500/30
                           transition transform hover:scale-105 text-base flex items-center space-x-2">
                        <i class="fas fa-utensils"></i>
                        <span>Feed Now</span>
                    </button>
                </div>
            </div>


            <h2 class="text-3xl font-bold mb-6 text-gray-800">
                Antrian Pakan Aktif
            </h2>
            <div class="bg-white rounded-xl p-8 shadow-2xl border border-gray-100">
                <h3 class="text-2xl text-gray-800 font-bold mb-6">Feeder Queue</h3>
                
                <table class="table-auto w-full border-collapse rounded-lg overflow-hidden border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                            <th class="px-6 py-3 border-b border-gray-200 w-1/3">Waktu</th>
                            <th class="px-6 py-3 border-b border-gray-200 w-1/3">Status</th>
                            <th class="px-6 py-3 border-b border-gray-200 w-1/3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="feederQueueBody" class="bg-white divide-y divide-gray-100 text-gray-700">
                        <tr><td class="px-6 py-4 text-center" colspan="3">Loading schedule...</td></tr>
                    </tbody>
                </table>

                <div class="mt-8 flex items-center space-x-2 text-sm pt-4 border-t border-gray-100">
                    <input type="checkbox" id="everydayScheduleBelow" class="text-green-600 focus:ring-green-500 rounded border-gray-300">
                    <label for="everydayScheduleBelow" class="font-medium cursor-pointer text-gray-700">Jadwalkan Setiap Hari</label>
                    <button id="everydayInfoBtn" class="ml-1 w-5 h-5 flex items-center justify-center text-xs bg-gray-200 text-gray-600 rounded-full hover:bg-gray-300 transition">
                        <i class="fas fa-info"></i>
                    </button>
                </div>
            </div>
            
        </div>
    </div>


    {{-- Replaced the custom div modal with a native <dialog> element for modern web practice --}}
    <dialog id="scheduleModal"
        class="rounded-xl shadow-2xl p-0 min-w-[90%] sm:min-w-[400px]">
        <div class="p-8 bg-white rounded-xl flex flex-col space-y-6">
            <h2 class="text-2xl font-bold text-gray-900">Jadwalkan Pakan ⏰</h2>
            <div class="mb-4">
                <label class="block font-semibold mb-2 text-gray-700">Waktu Pakan</label>
                <input type="time" id="feedingTime" class="w-full border-2 border-gray-300 rounded-lg p-3 text-base focus:ring-green-500 focus:border-green-500 transition duration-150">
            </div>
            <div class="flex justify-end space-x-3">
                <button id="cancelSchedule" type="button" class="px-5 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium transition">
                    Batal
                </button>
                <button id="confirmSchedule" type="button" class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow-md shadow-green-500/30 transition">
                    Jadwalkan
                </button>
            </div>
        </div>
    </dialog>

    <dialog id="everydayInfoModal" 
        class="rounded-xl shadow-2xl p-0 min-w-[90%] sm:min-w-[400px]">
        <div id="everydayInfoContent" class="p-8 bg-white rounded-xl flex flex-col space-y-4">
            <h2 class="text-2xl font-bold mb-3 text-gray-900">Fitur apa ini? 🤔</h2>
            <p class="text-gray-700 text-base mb-4">
                Jika <b>dicentang</b>, jadwal pakan yang Anda buat akan <b>otomatis</b> tersimpan dan dijalankan setiap hari.<br><br>
                Jika <b>tidak dicentang, jadwal hanya berlaku untuk hari ini dan akan dihapus otomatis setiap pukul 00:00.
            </p>
            <div class="flex justify-end pt-2">
                <button id="closeEverydayInfo" class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition">
                    Tutup
                </button>
            </div>
        </div>
    </dialog>


    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>

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

        // --- Firebase config ---
        const firebaseConfig = {
            apiKey:"AIzaSyCR6sq2t7VHvH0V4xdjns1nw70cazTB79U",
            authDomain:"iguppydemo.firebaseapp.com",
            databaseURL:"https://iguppydemo-default-rtdb.firebaseio.com",
            projectId:"iguppydemo",
            storageBucket:"iguppydemo.firebasestorage.app",
            messagingSenderId:"583888065638",
            appId:"1:583888065638:web:133c557e31c21e48c049a7"
        };
        firebase.initializeApp(firebaseConfig);
        const feederRef = firebase.database().ref('/feederQueue');

        // --- Modal controls (Using native dialog API) ---
        const scheduleModal = document.getElementById('scheduleModal');
        const everydayInfoModal = document.getElementById('everydayInfoModal');

        const openScheduleBtn = document.getElementById('openScheduleBtn');
        const cancelSchedule = document.getElementById('cancelSchedule');
        const confirmSchedule = document.getElementById('confirmSchedule');
        const feedNowBtn = document.getElementById('feedNowBtn');
        const everydayBelow = document.getElementById('everydayScheduleBelow');
        const infoBtn = document.getElementById('everydayInfoBtn');
        const closeInfo = document.getElementById('closeEverydayInfo');

        // Open/Close Modal functions for the Schedule Modal
        openScheduleBtn.addEventListener('click', () => scheduleModal.showModal());
        cancelSchedule.addEventListener('click', () => scheduleModal.close());
        
        // Open/Close Modal functions for the Info Modal
        infoBtn.addEventListener('click', () => everydayInfoModal.showModal());
        closeInfo.addEventListener('click', () => everydayInfoModal.close());


        // --- Confirm schedule (same logic, updated references for dialog) ---
        confirmSchedule.addEventListener('click', ()=>{
            const time = document.getElementById('feedingTime').value;
            if(!time) return alert('Pilih waktu terlebih dahulu');

            const newEntry = {
                time,
                status: 'Scheduled',
                everyday: everydayBelow.checked
            };

            feederRef.push(newEntry)
                .then(()=>{ scheduleModal.close(); })
                .catch(err => alert('Error: ' + err.message));
        });

        // --- Feed Now button (same logic) ---
        feedNowBtn.addEventListener('click', ()=>{
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: false };
            const timestamp = now.toLocaleTimeString('id-ID', timeOptions); // Use ID locale for HH:MM format

            const newEntry = {
                time: timestamp,
                status: 'Fed',
                everyday: everydayBelow.checked
            };
            feederRef.push(newEntry);
        });

        // --- Update queue table (rendering logic modified for new table structure) ---
        function renderQueue(data){
            const tbody = document.getElementById('feederQueueBody');
            tbody.innerHTML = '';
            
            if (Object.keys(data).length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td class="px-6 py-4 text-center text-gray-500 italic" colspan="3">Tidak ada jadwal pakan aktif.</td>`;
                tbody.appendChild(tr);
                return;
            }

            // Sort by time
            const sortedKeys = Object.keys(data).sort((a, b) => data[a].time.localeCompare(data[b].time));

            sortedKeys.forEach(key=>{
                const row = data[key];
                const isEveryday = row.everyday ? '<i class="fas fa-redo-alt text-green-500 ml-2" title="Jadwal Harian"></i>' : '';
                const statusColor = row.status === 'Scheduled' ? 'text-indigo-600 font-semibold' : 'text-gray-500 italic';
                
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition duration-150';
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${row.time}${isEveryday}</td>
                    <td class="px-6 py-4 whitespace-nowrap"><span class="${statusColor}">${row.status}</span></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="deleteSchedule('${key}')" class="text-red-600 hover:text-red-900 transition ml-4" title="Hapus Jadwal">
                             <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function deleteSchedule(key) {
            if (confirm('Yakin ingin menghapus jadwal ini?')) {
                feederRef.child(key).remove()
                    .then(() => console.log('Jadwal dihapus.'))
                    .catch(err => alert('Gagal menghapus jadwal: ' + err.message));
            }
        }


        feederRef.on('value', snapshot=>{
            const data = snapshot.val() || {};
            renderQueue(data);
        });

        // --- Remove non-everyday schedules at 00:00 (same logic) ---
        setInterval(()=>{
            const now = new Date();
            if(now.getHours() === 0 && now.getMinutes() === 0){
                feederRef.once('value').then(snapshot=>{
                    const data = snapshot.val() || {};
                    Object.keys(data).forEach(key=>{
                        // Only remove if it's NOT marked as everyday
                        if(!data[key].everyday){
                            feederRef.child(key).remove();
                        }
                    });
                });
            }
        },60000);

    </script>
</x-app-layout>