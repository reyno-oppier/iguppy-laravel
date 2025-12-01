@section('title', 'Dashboard - iGuppy | Sistem Monitoring Akuarium')
<x-app-layout>
    <button id="toggleSidebar" class="fixed top-4 left-4 text-white text-2xl z-50 bg-gray-800 p-2 rounded-md shadow-md hover:bg-gray-700 transition">
        ☰
    </button>

    <!-- LEFT SIDEBAR -->
    <aside id="sidebar" class="fixed top-0 left-0 h-full bg-gray-900 text-white shadow-xl flex flex-col items-center pt-20 w-20 transition-all duration-300 ease-in-out">
        <h1 id="sidebarTitle" class="text-2xl font-bold mb-8 overflow-hidden whitespace-nowrap opacity-0 transition-opacity duration-300 text-center w-full">iGuppy</h1>
        <nav class="space-y-4 flex flex-col items-center w-full">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-700 transition w-full active bg-gray-700 font-bold">
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


    <div id="mainContent" class="p-6 space-y-6 ml-20 transition-all duration-300 ease-in-out">
        <div id="toastContainer" class="fixed top-6 right-6 space-y-2 z-50"></div>
        <div id="tooltip" class="fixed px-3 py-1 text-sm rounded-md bg-black/70 text-white opacity-0 pointer-events-none transition-opacity duration-200 z-50"></div>

        <!-- Header Card -->
        <div class="bg-gray-800 text-white p-5 rounded-xl shadow-lg flex justify-between items-start mb-6">
            <div class="flex flex-col">
                <div id="clock" class="text-5xl font-bold leading-none"></div>
                <div id="date" class="text-lg opacity-80 mt-1"></div>

                <!-- Aquarium Buttons BELOW DATE -->
                <div class="mt-4 flex flex-col space-y-2">
                    <button id="aquarium1Btn" class="aquarium-btn px-3 py-1 rounded text-white">Aquarium 1</button>
                    <button id="aquarium2Btn" class="aquarium-btn px-3 py-1 rounded text-white">Aquarium 2</button>
                </div>
            </div>

            <div class="text-right mt-2">
                <span class="text-4xl font-bold">Hai, {{ auth()->user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- SENSOR WRAPPER CARD -->
        <div class="bg-blue-800 backdrop-blur-xl rounded-2xl p-6 shadow-xl mt-6 border border-white/10">
            <h2 class="text-3xl text-white font-bold mb-4">Kondisi Akuarium</h2>
            <div class="grid grid-cols-3 gap-6">
                <div id="tempCard" class="sensor-card text-white p-4 rounded-xl shadow-lg transition-all duration-500 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold">Suhu Air</h2>
                            <p id="tempValue" class="text-2xl mt-2">-- °C</p>
                        </div>
                        <div class="text-4xl">🌡️</div>
                    </div>
                    <p id="avgTemp" class="mt-2 text-sm font-medium text-gray-200 bg-gray-700/50 px-2 py-1 rounded">Avg Suhu (1 jam): -- °C</p>
                </div>

                <div id="phCard" class="sensor-card text-white p-4 rounded-xl shadow-lg transition-all duration-500 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold">pH Air</h2>
                            <p id="phValue" class="text-2xl mt-2">--</p>
                        </div>
                        <div class="text-4xl">⚗️</div>
                    </div>
                    <p id="avgPh" class="mt-2 text-sm font-medium text-gray-200 bg-gray-700/50 px-2 py-1 rounded">Avg pH (1 jam): --</p>
                </div>

                <div id="turbCard" class="sensor-card text-white p-4 rounded-xl shadow-lg transition-all duration-500 flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold">Kekeruhan</h2>
                            <p id="turbValue" class="text-2xl mt-2">-- NTU</p>
                        </div>
                        <div class="text-4xl">💧</div>
                    </div>
                    <p id="avgTurb" class="mt-2 text-sm font-medium text-gray-200 bg-gray-700/50 px-2 py-1 rounded">Avg Keruh (1 jam): -- NTU</p>
                </div>
            </div>

            <div class="text-white font-semibold text-lg text-center mt-2">
                Terakhir Berubah: <span id="lastUpdated">--:--:--</span>
            </div>
        </div>

        <!-- CHART WRAPPER CARD -->
        <div class="bg-blue-900 backdrop-blur-xl rounded-2xl p-6 shadow-xl mt-8 border border-white/10">
            <h2 class="text-xl text-white font-bold mb-4">Riwayat Kondisi</h2>
            <!-- RANGE FILTER -->
            <div class="flex justify-end space-x-2 mb-4">
                <button class="range-btn bg-gray-700 text-white px-3 py-1 rounded" data-range="1h">24 Jam</button>
                <button class="range-btn bg-gray-700 text-white px-3 py-1 rounded" data-range="7d">7 Hari</button>
                <button class="range-btn bg-gray-700 text-white px-3 py-1 rounded" data-range="30d">30 Hari</button>
            </div>
            <div class="grid grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="text-lg font-semibold mb-2">Riwayat Suhu</h3>
                    <canvas id="tempChart" height="200"></canvas>
                </div>
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="text-lg font-semibold mb-2">Riwayat pH</h3>
                    <canvas id="phChart" height="200"></canvas>
                </div>
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="text-lg font-semibold mb-2">Riwayat Kekeruhan</h3>
                    <canvas id="turbChart" height="200"></canvas>
                </div>
            </div>
            <div class="mt-4 text-right">
                <button id="downloadHistoryBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow">
                    Unduh Riwayat
                </button>
            </div>
        </div>

        <!-- PREDICTION CARD -->
        <div class="bg-gray-800 backdrop-blur-xl rounded-2xl p-6 shadow-xl mt-8 border border-white/10">
            <h2 class="text-3xl text-white font-bold mb-4">Prediksi Kondisi Akuarium</h2>
            <div class="grid grid-cols-3 gap-6">
                <!-- Predicted Temperature -->
                <div id="predTempCard" class="sensor-card text-white p-4 rounded-xl shadow-lg flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold">Suhu Air (Prediksi)</h2>
                            <p id="predTemp" class="text-2xl mt-2">
                                {{ isset($predictions['temperature']) ? number_format($predictions['temperature'], 1) : '--' }} °C
                            </p>
                        </div>
                        <div class="text-4xl">🌡️</div>
                    </div>
                </div>

                <!-- Predicted pH -->
                <div id="predPhCard" class="sensor-card text-white p-4 rounded-xl shadow-lg flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold">pH Air (Prediksi)</h2>
                            <p id="predPh" class="text-2xl mt-2">
                                {{ isset($predictions['ph']) ? number_format($predictions['ph'], 1) : '--' }}
                            </p>
                        </div>
                        <div class="text-4xl">⚗️</div>
                    </div>
                </div>

                <!-- Predicted Turbidity -->
                <div id="predTurbCard" class="sensor-card text-white p-4 rounded-xl shadow-lg flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold">Kekeruhan (Prediksi)</h2>
                            <p id="predTurb" class="text-2xl mt-2">
                                {{ isset($predictions['turbidity']) ? number_format($predictions['turbidity'], 1) : '--' }} NTU
                            </p>
                        </div>
                        <div class="text-4xl">💧</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Camera Preview Card -->
        <div class="bg-blue-800 backdrop-blur-xl rounded-2xl p-6 shadow-xl mt-8 border border-white/10">
            <h2 class="text-3xl text-white font-bold mb-4 text-center">Live-cam Akuarium</h2>
            <div class="flex justify-center items-center">
                <video id="liveCamera" autoplay playsinline class="rounded-xl shadow-lg w-full max-w-3xl border border-white/20"></video>
            </div>
        </div>



        <!-- Download History Modal -->
        <div id="downloadModal" class="fixed inset-0 bg-black/50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 z-50">
            <div id="downloadModalContent" class="bg-white rounded-xl shadow-xl p-6 w-96 transform scale-95 transition-all duration-300">
                <h2 class="text-xl font-bold mb-4">Unduh Riwayat</h2>
                <div class="mb-4">
                    <label class="block font-semibold mb-2">Durasi</label>
                    <select id="downloadDuration" class="w-full border rounded px-3 py-2">
                        <option value="30">30 Hari</option>
                        <option value="7">7 Hari</option>
                        <option value="1">24 Jam</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-2">Format</label>
                    <select id="downloadFormat" class="w-full border rounded px-3 py-2">
                        <option value="csv">CSV</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button id="cancelDownload" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
                    <button id="confirmDownload" class="px-4 py-2 rounded bg-green-500 hover:bg-green-600 text-white">Download</button>
                </div>
            </div>
        </div>

        <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-database-compat.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

            // Aquarium buttons
            let currentAquarium = 'aquarium1';
            const aquariumButtons = document.querySelectorAll('.aquarium-btn');
            const sensorCards = document.querySelectorAll('.sensor-card');

            function setActiveAquariumBtn(){
                aquariumButtons.forEach(btn => {
                    btn.style.backgroundColor = '#6b7280'; // grey for unpicked
                    btn.classList.remove('active');
                });
                const activeBtn = document.getElementById(currentAquarium+'Btn');
                activeBtn.classList.add('active');
                activeBtn.style.backgroundColor = currentAquarium==='aquarium1'?'#3b82f6':'#3b82f6'; // blue/blue
            }
            setActiveAquariumBtn();

            aquariumButtons.forEach(btn=>{
                btn.addEventListener('click',()=>{
                    if(currentAquarium===btn.id.replace('Btn','')) return;

                    // Animate sensor cards sliding
                    sensorCards.forEach(card=>{
                        card.style.transform = 'translateX(20px)';
                        card.style.opacity = '0';
                    });

                    setTimeout(()=>{
                        // Off previous listener (if exists)
                        try {
                            if (dbRef && typeof dbRef.off === 'function') dbRef.off();
                        } catch(e) {
                            console.warn('Failed to off previous dbRef', e);
                        }

                        // Switch aquarium ref
                        currentAquarium = btn.id.replace('Btn','');
                        setActiveAquariumBtn();
                        dbRef = firebase.database().ref(`/${currentAquarium}/sensors`);

                        // Re-attach listener and reload chart using currentRange
                        listenToFirebase();
                        loadChartRange(currentRange);

                        // animate back
                        sensorCards.forEach(card=>{
                            card.style.transform = 'translateX(0)';
                            card.style.opacity = '1';
                        });
                    },200);
                });
            });


            // Clock
            function updateClock(){
                const now = new Date();
                const time = now.toLocaleTimeString("en-US",{hour:"2-digit",minute:"2-digit",hour12:true});
                const date = now.toLocaleDateString("id-ID",{weekday:"long",year:"numeric",month:"long",day:"numeric"});
                document.getElementById("clock").textContent=time;
                document.getElementById("date").textContent=date;
            }
            setInterval(updateClock,1000); updateClock();

            // Firebase config
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
            let dbRef = firebase.database().ref(`/${currentAquarium}/sensors`);

            function calculateAvg(history, key){
                const oneHourAgo = Date.now() - 3600*1000; // 1 hour in milliseconds
                let values = [];

                // Iterate over each record inside history (record001, record002, ...)
                for (let recordId in history) {
                    const record = history[recordId];
                    if (record[key] !== undefined && new Date(record.waktu).getTime() >= oneHourAgo) {
                        values.push(record[key]);
                    }
                }

                if (values.length === 0) return null;
                return (values.reduce((a,b) => a+b, 0) / values.length).toFixed(2);
            }


            const tooltip = document.getElementById("tooltip");
            document.querySelectorAll(".sensor-card").forEach(card=>{
                card.addEventListener("mousemove",e=>{
                    tooltip.style.left=e.pageX+15+"px";
                    tooltip.style.top=e.pageY+15+"px";
                });
                card.addEventListener("mouseenter",()=>{tooltip.textContent=card.dataset.tooltip; tooltip.classList.remove("opacity-0");});
                card.addEventListener("mouseleave",()=>{tooltip.classList.add("opacity-0");});
            });

            const tempCtx=document.getElementById('tempChart').getContext('2d');
            const phCtx=document.getElementById('phChart').getContext('2d');
            const turbCtx=document.getElementById('turbChart').getContext('2d');
            let tempChart=new Chart(tempCtx,{type:'line',data:{labels:[],datasets:[{label:'Temperature °C',data:[]}]}}); 
            let phChart=new Chart(phCtx,{type:'line',data:{labels:[],datasets:[{label:'pH',data:[]}]}}); 
            let turbChart=new Chart(turbCtx,{type:'line',data:{labels:[],datasets:[{label:'Turbidity NTU',data:[]}]}});

            let currentRange = 30; // default awal 30 hari

            window.addEventListener('DOMContentLoaded', () => {
                const temp = {{ $predictions['temperature'] ?? 'null' }};
                const ph = {{ $predictions['ph'] ?? 'null' }};
                const turb = {{ $predictions['turbidity'] ?? 'null' }};

                // Temperature card
                const tempCard = document.getElementById('predTempCard');
                if(temp !== null){
                    if(temp < 20) tempCard.classList.add('bg-red-400');
                    else if(temp < 22 || temp > 28) tempCard.classList.add('bg-yellow-300');
                    else if(temp > 30) tempCard.classList.add('bg-red-400');
                    else tempCard.classList.add('bg-green-400');
                }

                // pH card
                const phCard = document.getElementById('predPhCard');
                if(ph !== null){
                    if(ph < 6.5 || ph > 8.0) phCard.classList.add('bg-red-400');
                    else if((ph >= 6.5 && ph <= 6.8) || (ph >= 7.8 && ph <= 8.0)) phCard.classList.add('bg-yellow-300');
                    else phCard.classList.add('bg-green-400');
                }

                // Turbidity card
                const turbCard = document.getElementById('predTurbCard');
                if(turb !== null){
                    if(turb > 40) turbCard.classList.add('bg-red-400');
                    else if(turb > 25) turbCard.classList.add('bg-yellow-300');
                    else turbCard.classList.add('bg-green-400');
                }
            });

            // Saat tombol ditekan, ganti range
            const rangeButtons = document.querySelectorAll(".range-btn");

            console.log("Range buttons found:", rangeButtons.length);

            rangeButtons.forEach(button => {
                button.addEventListener("click", function () {

                    // reset warna
                    rangeButtons.forEach(btn => {
                        btn.classList.remove("bg-green-600");
                        btn.classList.add("bg-gray-700");
                    });

                    // warna tombol aktif
                    this.classList.remove("bg-gray-700");
                    this.classList.add("bg-green-600");

                    // run function
                    const range = this.dataset.range;
                    loadChartRange(range);
                });
            });

            function avg(arr) {
                        return arr.reduce((a, b) => a + b, 0) / arr.length;
                    }

                    function updateCharts(labels, temp, ph, turb) {
                tempChart.data.labels = labels;
                tempChart.data.datasets[0].data = temp;
                tempChart.update();

                phChart.data.labels = labels;
                phChart.data.datasets[0].data = ph;
                phChart.update();

                turbChart.data.labels = labels;
                turbChart.data.datasets[0].data = turb;
                turbChart.update();
            }

            function loadChartRange(range) {

                const now = Date.now();
                let rangeMs = 0;

                // Tentukan range milidetik
                if (range === "1h")  rangeMs = 1  * 60 * 60 * 1000;
                if (range === "24h") rangeMs = 24 * 60 * 60 * 1000;
                if (range === "7d")  rangeMs = 7  * 24 * 60 * 60 * 1000;
                if (range === "30d") rangeMs = 30 * 24 * 60 * 60 * 1000;

                dbRef.once('value', snap => {
                    const history = snap.val();
                    let grouped = {};

                    for (let id in history) {
                        let d = history[id];
                        let t = new Date(d.waktu);
                        let ts = t.getTime();

                        // Skip jika lebih tua dari range
                        if (now - ts > rangeMs) continue;

                        let key;

                        // --- GROUP PER JAM ---
                        if (range === "1h" || range === "24h") {
                            const hour = t.getHours().toString().padStart(2, '0');
                            key = t.toISOString().split("T")[0] + "-" + hour; 
                        }

                        // --- GROUP PER HARI ---
                        else if (range === "7d" || range === "30d") {
                            key = t.toISOString().split("T")[0];
                        }

                        if (!grouped[key]) grouped[key] = { temp: [], ph: [], turb: [] };

                        grouped[key].temp.push(d.temperature);
                        grouped[key].ph.push(d.ph);
                        grouped[key].turb.push(d.turbidity);
                    }

                    // Convert grouped → arrays
                    let labels = Object.keys(grouped).sort();
                    let tempData = labels.map(k => avg(grouped[k].temp));
                    let phData   = labels.map(k => avg(grouped[k].ph));
                    let turbData = labels.map(k => avg(grouped[k].turb));

                    updateCharts(labels, tempData, phData, turbData);
                });
            }

            loadChartRange();



            function listenToFirebase() {
                // Pastikan tidak terduplikasi
                if (dbRef && typeof dbRef.off === 'function') dbRef.off();

                dbRef.on('value', (snapshot) => {
                    const recordsData = snapshot.val();
                    if (!recordsData) return;

                    const recordKeys = Object.keys(recordsData).sort();
                    const latestRecord = recordsData[recordKeys[recordKeys.length - 1]];

                    const temperature = latestRecord.temperature;
                    const ph = latestRecord.ph;
                    const turbidity = latestRecord.turbidity;

                    // Update latest values
                    document.getElementById('lastUpdated').textContent = new Date(latestRecord.waktu).toLocaleTimeString();
                    document.getElementById('tempValue').textContent = temperature + " °C";
                    document.getElementById('phValue').textContent = ph;
                    document.getElementById('turbValue').textContent = turbidity;

                    // Calculate averages for the last 1 hour (unchanged)
                    function calculateAvg(records, key){
                        const oneHourAgo = Date.now() - 3600 * 1000;
                        let values = [];

                        for(let k in records){
                            const record = records[k];
                            if(record[key] !== undefined){
                                const recordTime = new Date(record.waktu).getTime();
                                if(recordTime >= oneHourAgo) values.push(record[key]);
                            }
                        }

                        // If no data in last hour, use all records
                        if(values.length === 0){
                            for(let k in records){
                                const record = records[k];
                                if(record[key] !== undefined) values.push(record[key]);
                            }
                        }

                        if(values.length === 0) return null;
                        return (values.reduce((a,b)=>a+b,0)/values.length).toFixed(2);
                    }

                    const avgTemp = calculateAvg(recordsData, 'temperature');
                    const avgPh = calculateAvg(recordsData, 'ph');
                    const avgTurb = calculateAvg(recordsData, 'turbidity');

                    document.getElementById('avgTemp').textContent = avgTemp ? "Rata-rata Suhu: " + avgTemp + " °C" : "-- °C";
                    document.getElementById('avgPh').textContent = avgPh ? "Rata-rata pH: " + avgPh : "--";
                    document.getElementById('avgTurb').textContent = avgTurb ? "Rata-rata Kekeruhan: " + avgTurb + " NTU" : "-- NTU";

                    // Set card statuses (kehilangan chart-update di sini)
                    function setCardStatus(card, condition, tooltipText) {
                        card.className = 'sensor-card text-white p-4 rounded-xl shadow-lg flex flex-col justify-between transition-all duration-500';
                        card.classList.add(condition);
                        card.dataset.tooltip = tooltipText;
                    }

                    if (temperature < 20) setCardStatus(tempCard, 'bg-red-400', 'Status: Air Sangat Dingin ❄️');
                    else if (temperature < 22) setCardStatus(tempCard, 'bg-yellow-300', 'Status: Air Agak Dingin 🌥️');
                    else if (temperature > 30) setCardStatus(tempCard, 'bg-red-400', 'Status: Air Sangat Panas 🔥');
                    else if (temperature > 28) setCardStatus(tempCard, 'bg-yellow-300', 'Status: Air Agak Panas 🌤️');
                    else setCardStatus(tempCard, 'bg-green-400', 'Status: Suhu Normal 👍');

                    if (ph < 6.5) setCardStatus(phCard, 'bg-red-400', 'Status: pH Air Rendah ⚠️');
                    else if (ph > 8.0) setCardStatus(phCard, 'bg-red-400', 'Status: pH Air Tinggi ⚠️');
                    else if ((ph >= 6.5 && ph <= 6.8) || (ph >= 7.8 && ph <= 8.0)) setCardStatus(phCard, 'bg-yellow-300', 'Status: pH Mendekati Batas ⚠️');
                    else setCardStatus(phCard, 'bg-green-400', 'Status: pH Normal 👍');

                    if (turbidity > 40) setCardStatus(turbCard, 'bg-red-400', 'Status: Air Sangat Keruh ❌');
                    else if (turbidity > 25) setCardStatus(turbCard, 'bg-yellow-300', 'Status: Air Keruh ⚠️');
                    else setCardStatus(turbCard, 'bg-green-400', 'Status: Air Jernih 👍');

                    // --- PENTING: jangan update chart langsung di sini ---
                    // Panggil loadChartRange agar chart selalu didasarkan pada currentRange
                    try {
                        loadChartRange(currentRange);
                    } catch(e) {
                        console.error("loadChartRange error:", e);
                    }
                });
            }

            listenToFirebase();

            // Modal
            const downloadBtn = document.getElementById('downloadHistoryBtn');
            const modal = document.getElementById('downloadModal');
            const modalContent = document.getElementById('downloadModalContent');
            const cancelBtn = document.getElementById('cancelDownload');
            const confirmBtn = document.getElementById('confirmDownload');

            // Open modal
            downloadBtn.addEventListener('click', () => {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                setTimeout(() => {
                    modal.classList.add('opacity-100');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 10);
            });

            // Close modal function
            function closeModal(){
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
                modal.classList.remove('opacity-100');
                // Wait for transition to finish before disabling pointer events
                setTimeout(() => {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                }, 300); // match your Tailwind duration-300
            }

            // Cancel button
            cancelBtn.addEventListener('click', closeModal);

                // Confirm button (also closes modal)
                confirmBtn.addEventListener('click', () => {
                const duration = parseInt(document.getElementById('downloadDuration').value);
                const format = document.getElementById('downloadFormat').value;

                const now = Date.now();
                const durationMs = duration === 1 ? 24*3600*1000 : duration*24*3600*1000;

                dbRef.once('value').then(snapshot => {
                    const records = snapshot.val();
                    if(!records) return alert('No data available');

                    const filtered = Object.values(records).filter(r => (new Date(r.waktu).getTime() >= now - durationMs));
                    if(filtered.length === 0) return alert('No data for selected duration');

                    if(format === 'csv') downloadCSV(filtered);
                    else if(format === 'pdf') downloadPDF(filtered);

                    closeModal();
                });
            });

            // Download function
            confirmBtn.addEventListener('click', () => {
                const duration = parseInt(document.getElementById('downloadDuration').value);
                const format = document.getElementById('downloadFormat').value;

                const now = Date.now();
                const durationMs = duration === 1 ? 24*3600*1000 : duration*24*3600*1000;

                dbRef.once('value').then(snapshot => {
                    const records = snapshot.val();
                    if(!records) return alert('No data available');

                    const filtered = Object.values(records).filter(r => (new Date(r.waktu).getTime() >= now - durationMs));

                    // Only call one function per click
                    if(filtered.length === 0){
                        alert('No data available for selected duration');
                        return;
                    }

                    if(format === 'csv'){
                        downloadCSV(filtered);   // single download
                    } else if(format === 'pdf'){
                        downloadPDF(filtered);   // single download
                    }

                    closeModal();  // close after download
                });
            });


            // CSV Export
            function downloadCSV(data){
                let csv = 'Waktu,Temperature,PH,Turbidity\n';
                data.forEach(r => {
                    csv += `${r.waktu},${r.temperature},${r.ph},${r.turbidity}\n`;
                });

                const blob = new Blob([csv], {type: 'text/csv'});
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'aquarium_history.csv';
                a.click();
                URL.revokeObjectURL(url);
            }

            // PDF Export
            function downloadPDF(data){
                // Load jsPDF via CDN
                if(!window.jsPDF){
                    const script = document.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
                    script.onload = () => createPDF(data);
                    document.body.appendChild(script);
                } else createPDF(data);
            }

            function createPDF(data){
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                doc.setFontSize(18);
                doc.text('iGuppy Aquarium History', 14, 20);
                doc.setFontSize(12);
                doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 28);

                const tableColumn = ["Waktu", "Temperature (°C)", "pH", "Turbidity (NTU)"];
                const tableRows = [];

                data.forEach(r => {
                    tableRows.push([r.waktu, r.temperature, r.ph, r.turbidity]);
                });

                // AutoTable for PDF
                if(window.jspdf && doc.autoTable){
                    doc.autoTable({
                        startY: 35,
                        head: [tableColumn],
                        body: tableRows,
                        theme: 'grid'
                    });
                } else {
                    // Simple manual table
                    let y = 35;
                    doc.text(tableColumn.join(' | '), 14, y);
                    y += 8;
                    tableRows.forEach(row => {
                        doc.text(row.join(' | '), 14, y);
                        y += 8;
                    });
                }

                doc.save('aquarium_history.pdf');
            }

            async function fetchPredictions() {
                try {
                    const res = await fetch('http://localhost/predict'); // <-- full Laravel URL
                    const data = await res.json();

                    if (!data.error) {
                        document.getElementById('predTemp').textContent = data.temperature.toFixed(2) + ' °C';
                        document.getElementById('predPh').textContent = data.ph.toFixed(2);
                        document.getElementById('predTurb').textContent = data.turbidity.toFixed(2) + ' NTU';
                    } else {
                        console.error('Prediction error:', data.error);
                    }
                } catch (e) {
                    console.error('Failed to fetch predictions:', e);
                }
            }

            // Fetch immediately and then every 30s
            fetchPredictions();
            setInterval(fetchPredictions, 30000);

            async function startCamera() {
                const video = document.getElementById('liveCamera');
                try {
                    // Request access to the camera
                    const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                    video.srcObject = stream;
                } catch (err) {
                    console.error("Error accessing camera:", err);
                    video.insertAdjacentHTML('afterend', '<p class="text-red-400 mt-2">Camera access denied or not available.</p>');
                }
            }

            window.addEventListener('DOMContentLoaded', startCamera);



        </script>

        <style>
            @keyframes slide-in{from{transform:translateX(100%);opacity:0;}to{transform:translateX(0);opacity:1;}}
            @keyframes slide-out{from{transform:translateX(0);opacity:1;}to{transform:translateX(100%);opacity:0;}}
            .animate-slide-in{animation:slide-in 0.5s forwards;}
            .animate-slide-out{animation:slide-out 0.5s forwards;}
            aside nav a.active{background-color:#1f2937;font-weight:bold;}
        </style>
</x-app-layout>
