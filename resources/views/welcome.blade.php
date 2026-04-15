<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>VETRA Clinical Portal </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        .heading-style { font-weight: 900; text-transform: uppercase; letter-spacing: -0.02em; font-style: italic; }
        .label-style { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; }
        .hidden-view { display: none; }
        .active-nav { color: #dc2626 !important; position: relative; font-weight: 800; }
        .active-nav::after { content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 3px; background: #dc2626; border-radius: 2px; }
        .view-container { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        #global-modal, #profile-modal, #history-modal { display: none; align-items: center; justify-content: center; z-index: 1000; }
        #global-modal.active, #profile-modal.active, #history-modal.active { display: flex; }
        .brand-logo { height: 50px; width: auto; object-fit: contain; }
        .brand-logo-large { height: 100px; width: auto; object-fit: contain; }
    </style>
</head>
<body class="selection:bg-red-100 selection:text-red-600">

<div id="auth-gate" class="min-h-screen relative flex items-center justify-center p-6 overflow-hidden bg-slate-50">
        
        <div class="absolute top-10 left-1/4 w-96 h-96 bg-red-500/20 rounded-full mix-blend-multiply filter blur-[100px] animate-pulse [animation-duration:4s]"></div>
        <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full mix-blend-multiply filter blur-[100px] animate-pulse [animation-duration:7s]"></div>
        <div class="absolute bottom-10 left-1/3 w-96 h-96 bg-slate-500/20 rounded-full mix-blend-multiply filter blur-[100px] animate-pulse [animation-duration:5s]"></div>

        <div class="relative w-full max-w-[420px] bg-slate-200/70 backdrop-blur-3xl rounded-[2.5rem] p-10 shadow-[0_8px_40px_rgb(0,0,0,0.12)] border border-slate-300/50">
            
            <div class="text-center mb-8">
                <img src="vetralogo.png" alt="VETRA Logo" class="mx-auto h-32 w-auto object-contain mb-6 drop-shadow-[0_0_30px_rgba(255,255,255,1)] transform hover:scale-105 transition-transform duration-300">
                
                <h2 id="auth-heading" class="text-2xl font-black text-slate-800 tracking-tight">Access Portal</h2>
                <p id="auth-subheading" class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">Vetra Clinical System</p>
            </div>

            <form id="auth-form" class="space-y-4" onsubmit="handleAuth(event)">
                
                <div id="name-field" class="hidden space-y-1">
                    <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest pl-2">Operator Name</label>
                    <input id="auth-name" type="text" class="w-full bg-white/80 border border-slate-300/50 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all placeholder-slate-400" placeholder="e.g. Dr. Sarah">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest pl-2">ID</label>
                    <input required id="auth-user" type="text" class="w-full bg-white/80 border border-slate-300/50 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all placeholder-slate-400" placeholder="clinical_id">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest pl-2">Passcode</label>
                    <input required id="auth-pass" type="password" class="w-full bg-white/80 border border-slate-300/50 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all placeholder-slate-400" placeholder="••••••••">
                </div>

                <div id="confirm-field" class="hidden space-y-1">
                    <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest pl-2">Verify Passcode</label>
                    <input id="auth-pass-confirm" type="password" class="w-full bg-white/80 border border-slate-300/50 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all placeholder-slate-400" placeholder="••••••••">
                </div>

                <p id="auth-error" class="text-[10px] text-red-600 font-black uppercase tracking-wide text-center hidden bg-red-50 p-3 rounded-xl border border-red-100 shadow-sm"></p>

                <div class="pt-4">
                    <button type="submit" id="auth-btn" class="w-full bg-gradient-to-r from-slate-900 to-slate-800 hover:from-slate-800 hover:to-slate-700 text-white py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-xs shadow-[0_8px_20px_rgb(0,0,0,0.12)] transition-all hover:-translate-y-0.5">
                        Authenticate
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center flex flex-col gap-2">
                <button type="button" id="toggle-auth-btn" onclick="toggleAuthMode()" class="text-[10px] font-black text-slate-500 hover:text-red-600 uppercase tracking-widest transition-colors">
                    Register New Operator
                </button>
            </div>
        </div>
    </div>

    <div id="app-shell" class="hidden">
        <header class="bg-white px-10 h-20 flex items-center justify-between border-b border-slate-100 sticky top-0 z-50">
            <div class="flex items-center gap-12">
                <div class="flex items-center cursor-pointer bg-slate-800 p-2 rounded-lg" onclick="setView('Dashboard')">
                <img src="vetralogo.png" alt="System Logo" class="brand-logo-large">
                </div>
                <nav class="flex gap-10">
                    <button onclick="setView('Dashboard')" id="nav-Dashboard" class="nav-btn text-[13px] font-bold text-slate-400">Dashboard</button>
                    <button onclick="setView('Owner')" id="nav-Owner" class="nav-btn text-[13px] font-bold text-slate-400">Owner</button>
                    <button onclick="setView('Patient')" id="nav-Patient" class="nav-btn text-[13px] font-bold text-slate-400">Patient</button>
                    <button onclick="setView('Inventory')" id="nav-Inventory" class="nav-btn text-[13px] font-bold text-slate-400">Inventory</button>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right mr-4 hidden sm:block">
                    <p class="label-style">Authorized Personnel</p>
                    <p id="current-user-display" class="text-xs font-bold text-slate-600 uppercase tracking-tight">Operator</p>
                </div>
                <div onclick="openProfileModal()" class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center text-white shadow-lg cursor-pointer hover:bg-red-700 transition-colors">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
                <div onclick="logout()" class="ml-2 text-slate-300 hover:text-red-600 cursor-pointer transition-colors">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </div>
            </div>
        </header>

        <main class="w-full max-w-[1400px] mx-auto p-8 md:p-12">
            <div id="view-Dashboard" class="view-container hidden-view space-y-12">
                <h1 class="text-4xl font-black uppercase tracking-tight italic text-slate-800">CLINICAL <span class="text-red-600">OVERVIEW</span></h1>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="dashboard-stats"></div>
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-10 py-8 flex justify-between items-center">
                        <h2 class="text-xl font-black italic uppercase text-slate-800 tracking-tight">Recent Patient Activity</h2>
                        <button onclick="setView('Patient')" class="text-[10px] font-black text-red-600 uppercase tracking-widest hover:underline">View All</button>
                    </div>
                    <div id="activity-feed" class="pb-6"></div>
                </div>
            </div>

            <div id="view-Registry" class="view-container hidden-view space-y-10">
                <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-sm p-12">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
                        <h1 id="registry-main-title" class="text-4xl font-black uppercase tracking-tighter italic text-slate-300">REGISTRY <span class="text-red-600">CLUSTER</span></h1>
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <div class="relative flex-1 md:w-80">
                                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 w-4 h-4"></i>
                                <input type="text" id="registry-search" oninput="handleSearch(this.value)" placeholder="Search..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl outline-none text-sm font-medium">
                            </div>
                            <button id="add-record-btn" class="bg-[#E11D48] text-white px-6 py-3 rounded-xl font-black uppercase text-[11px] tracking-widest flex items-center gap-2 shadow-lg">+ NEW RECORDS</button>
                        </div>
                    </div>
                    <div id="registry-headings" class="grid grid-cols-12 px-6 mb-8 border-b border-slate-50 pb-4"></div>
                    <div id="registry-list" class="space-y-4"></div>
                </div>
            </div>

            <div id="view-Inventory" class="view-container hidden-view space-y-8">
                <h1 class="text-4xl font-black uppercase tracking-tight italic text-slate-800">Inventory <span class="text-red-600">Management</span></h1>
               <div id="inventory-banner" class="hidden bg-[#FFFBEB] border border-[#FEF3C7] rounded-2xl p-4 flex items-center gap-3">
                    <i data-lucide="alert-triangle" class="text-amber-500 w-5 h-5"></i>
                    <p id="inventory-banner-text" class="text-amber-800 text-xs font-bold uppercase tracking-tight opacity-70"></p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="inventory-grid"></div>
            </div>
        </main>
    </div>

    <div id="global-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl">
            <div class="flex justify-between items-center mb-8">
                <h2 id="modal-title" class="text-2xl text-slate-800 heading-style">Record Details</h2>
                <button onclick="closeModal()" class="text-slate-300 hover:text-red-600 transition-colors"><i data-lucide="x"></i></button>
            </div>
            <form id="modal-form" onsubmit="handleFormSubmit(event)" class="space-y-6">
                <div id="modal-form-fields" class="space-y-4 max-h-[60vh] overflow-y-auto px-1"></div>
                <button type="submit" class="w-full bg-red-600 text-white py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl">Commit Record</button>
            </form>
        </div>
    </div>

    <div id="history-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-3xl rounded-[2.5rem] p-10 shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-8">
                <div><h2 class="text-2xl text-slate-800 heading-style">Medical History</h2><p id="history-patient-name" class="label-style text-red-600"></p></div>
                <button onclick="closeHistoryModal()" class="text-slate-300 hover:text-red-600 transition-colors"><i data-lucide="x"></i></button>
            </div>
            <div class="bg-slate-50 p-6 rounded-[1.5rem] mb-8 border border-slate-100">
                <p id="hist-form-title" class="label-style mb-4">Add Clinic Entry</p>
<form id="medical-form" onsubmit="addMedicalRecord(event)" class="space-y-4">
                    <input id="hist-problem" placeholder="Problem" class="w-full bg-white rounded-xl px-4 py-3 text-sm font-bold border-none outline-none">
                    <textarea id="hist-action" placeholder="Action" class="w-full bg-white rounded-xl px-4 py-3 text-sm font-bold border-none outline-none resize-none" rows="2"></textarea>
                    
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <input list="med-options" id="hist-medicine-search" placeholder="Search Medicine/Food..." class="w-full bg-white rounded-xl px-4 py-3 text-sm font-bold border-none outline-none">
                            <datalist id="med-options"></datalist>
                        </div>
                        <input type="number" id="hist-quantity" placeholder="Qty" min="1" class="w-24 bg-white rounded-xl px-4 py-3 text-sm font-bold border-none outline-none">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" id="hist-submit-btn" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest">Add Record</button>
                        <button type="button" id="hist-cancel-btn" onclick="resetMedicalForm()" class="hidden bg-slate-200 text-slate-600 px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest">Cancel</button>
                    </div>
                </form>
            </div>
            <div id="medical-records-list" class="space-y-6"></div>
        </div>
    </div>
    <div id="profile-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl text-slate-800 heading-style">Operator Profile</h2>
                <button type="button" onclick="closeProfileModal()" class="text-slate-300 hover:text-red-600 transition-colors"><i data-lucide="x"></i></button>
            </div>
            <form id="profile-form" onsubmit="handleProfileUpdate(event)" class="space-y-6">
                <div class="space-y-4">
                    <div class="space-y-1"><label class="label-style">Display Name</label><input required id="prof-name" class="w-full bg-slate-50 border-none outline-none rounded-2xl px-6 py-4 font-bold text-slate-700"></div>
                    <div class="space-y-1"><label class="label-style">ID</label><input required id="prof-user" class="w-full bg-slate-50 border-none outline-none rounded-2xl px-6 py-4 font-bold text-slate-700"></div>
                    <div class="space-y-1"><label class="label-style">Email Address</label><input required id="prof-email" type="email" class="w-full bg-slate-50 border-none outline-none rounded-2xl px-6 py-4 font-bold text-slate-700"></div>
                    
                    <div class="pt-4 border-t border-slate-100 space-y-4">
                        <p class="label-style text-slate-400">Change Password (Leave blank to keep current)</p>
                        <div class="space-y-1"><label class="label-style">New Password</label><input type="password" id="prof-pass" class="w-full bg-slate-50 border-none outline-none rounded-2xl px-6 py-4 font-bold text-slate-700" placeholder="••••••••"></div>
                        <div class="space-y-1"><label class="label-style">Confirm New Password</label><input type="password" id="prof-pass-confirm" class="w-full bg-slate-50 border-none outline-none rounded-2xl px-6 py-4 font-bold text-slate-700" placeholder="••••••••"></div>
                    </div>
                </div>
                <p id="prof-error" class="text-[10px] text-red-600 font-bold uppercase text-center hidden"></p>
                <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] mt-4 shadow-xl">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        let currentType = 'Dashboard', authMode = 'LOGIN', editMode = false, activeIdx = null, currentUser = null, historyActiveIdx = null, historyEditIdx = null;
        let data = { patients: [], owners: [], inventory: [], recentActivity: [] };

        document.addEventListener("DOMContentLoaded", () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if(csrfToken) window.csrfToken = csrfToken.getAttribute('content');
        });

        async function apiCall(url, method = 'POST', payload = {}) {
            const options = { method: method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' } };
            if (method !== 'GET') options.body = JSON.stringify(payload);
            const response = await fetch(url, options);
            return await response.json();
        }

        async function loadData() {
            try {
                const res = await apiCall('/api/data', 'GET');
                data.patients = res.patients || []; 
                data.owners = res.owners || []; 
                data.inventory = res.inventory || [];
                data.recentActivity = res.recent || []; 
            } catch (e) {
                console.error("Failed to load data.");
            }
        }

        window.onload = async () => {
            try {
                const res = await apiCall('/api/data', 'GET');
                if(res.user) {
                    currentUser = res.user;
                    data.patients = res.patients || []; 
                    data.owners = res.owners || []; 
                    data.inventory = res.inventory || [];
                    data.recentActivity = res.recent || []; 
                    document.getElementById('auth-gate').style.display = 'flex'; 
                    document.getElementById('app-shell').style.display = 'none';
                    document.getElementById('auth-gate').style.display = 'none'; 
                    document.getElementById('app-shell').style.display = 'block'; 
                    document.getElementById('current-user-display').innerText = currentUser.name; 
                    setView('Dashboard');
                    
                }
            } catch (e) {}
        }

    function toggleAuthMode() {
            authMode = authMode === 'LOGIN' ? 'SIGNUP' : 'LOGIN';
            
            document.getElementById('name-field').classList.toggle('hidden');
            document.getElementById('confirm-field').classList.toggle('hidden');
            
            document.getElementById('auth-heading').innerText = authMode === 'LOGIN' ? 'Access Portal' : 'New Enrollment';
            document.getElementById('auth-subheading').innerText = authMode === 'LOGIN' ? 'Vetra Clinical System' : 'Register Operator Profile';
            
            document.getElementById('auth-btn').innerText = authMode === 'LOGIN' ? 'Authenticate' : 'Create Profile';
            document.getElementById('toggle-auth-btn').innerText = authMode === 'LOGIN' ? 'Register New Operator' : 'Return to Login';
            

            document.getElementById('auth-error').classList.add('hidden');
        }


       async function handleAuth(e) {
            e.preventDefault();
            const u = document.getElementById('auth-user').value;
            const p = document.getElementById('auth-pass').value;
            const pc = document.getElementById('auth-pass-confirm').value;
            const n = document.getElementById('auth-name').value;
            const error = document.getElementById('auth-error');
            

            error.classList.add('hidden');

            if (authMode === 'SIGNUP') {
                if (!u || !p) { error.innerText = "Please fill out all required fields!"; error.classList.remove('hidden'); return; }
                if (p !== pc) { error.innerText = "Passwords do not match!"; error.classList.remove('hidden'); return; }
                
                try {
                    const res = await apiCall('/api/register', 'POST', { name: n, username: u, password: p });
                    
                    if (res.success) { 
                        alert("Account Created Successfully! You can now log in."); 
                        toggleAuthMode(); 
                        
                        document.getElementById('auth-pass').value = '';
                        document.getElementById('auth-pass-confirm').value = '';
                    } else {
                        error.innerText = res.error || "Registration failed."; 
                        error.classList.remove('hidden');
                    }
                } catch(err) {
                    error.innerText = "Server Error during registration."; 
                    error.classList.remove('hidden');
                }
            } else {
                try {
                    const res = await apiCall('/api/login', 'POST', { username: u, password: p });
                    if (res.success) { 
                        currentUser = res.user; 
                        document.getElementById('auth-gate').style.display = 'none'; 
                        document.getElementById('app-shell').style.display = 'block'; 
                        document.getElementById('current-user-display').innerText = currentUser.name; 
                        setView('Dashboard'); 
                        await loadData(); 
                        setView('Dashboard'); 
                    } else { 
                        error.innerText = "Access Denied. Incorrect username or password."; 
                        error.classList.remove('hidden'); 
                    }
                } catch(err) {
                    error.innerText = "Server error during login.";
                    error.classList.remove('hidden');
                }
            }
        }


        async function logout() { 
    try { 
        await fetch('/api/logout', { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 
                'Accept': 'application/json' 
            } 
        }); 
    } catch (e) {
        console.error("Logout error", e);
    }
    
    if (typeof currentUser !== 'undefined') currentUser = null;
    if (typeof data !== 'undefined') data = null;
    
    window.location.replace('/'); 
}

function handleSearch(query) {
    if (currentType === 'Patient' || currentType === 'Owner') {
        renderRegistry(currentType, query);
    }
}

function setView(v) {
    currentType = v; 
    document.querySelectorAll('.view-container').forEach(c => c.classList.add('hidden-view')); 
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active-nav'));
    
    const searchInput = document.getElementById('registry-search');
    if (searchInput) searchInput.value = '';

    let target = `view-${v}`; 
    if (v === 'Patient' || v === 'Owner') { 
        target = 'view-Registry'; 
        renderRegistry(v); 
        document.getElementById('add-record-btn').onclick = () => openModal(v); 
    }
    else if (v === 'Dashboard') renderDashboard(); 
    else if (v === 'Inventory') renderInventory();
    
    document.getElementById(target).classList.remove('hidden-view'); 
    document.getElementById(`nav-${v}`).classList.add('active-nav'); 
    lucide.createIcons();
}

        function renderDashboard() {
            const totalClinicalRecords = data.patients.reduce((sum, p) => sum + (p.records ? p.records.length : 0), 0);
            const stats = [
                { label: 'Total Pets', val: data.patients.length, sub: 'Registered pets', color: 'bg-blue-500', icon: 'dog' },
                { label: 'Medical Records', val: totalClinicalRecords, sub: 'Total clinical entries', color: 'bg-indigo-500', icon: 'list-checks' },
                { label: 'Inventory Items', val: data.inventory.length, sub: 'In stock', color: 'bg-emerald-500', icon: 'box' },
                { label: 'Low Stock Alerts', val: data.inventory.filter(i=>i.critical).length, sub: 'Needs attention', color: 'bg-red-500', icon: 'alert-triangle' }
            ];
            document.getElementById('dashboard-stats').innerHTML = stats.map(s => `<div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-50 flex flex-col justify-between h-48 hover:-translate-y-1 transition-all"><div class="flex justify-between items-start"><p class="text-[12px] font-bold text-slate-500">${s.label}</p><div class="w-10 h-10 rounded-xl ${s.color} flex items-center justify-center text-white shadow-lg opacity-80"><i data-lucide="${s.icon}" class="w-5 h-5"></i></div></div><div><h3 class="text-4xl font-black text-slate-800">${s.val}</h3><p class="text-[11px] font-bold text-slate-400 mt-1">${s.sub}</p></div></div>`).join('');
            
            document.getElementById('activity-feed').innerHTML = data.recentActivity.map(p => `
                <div class="flex items-center justify-between px-10 py-5 border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center">
                            <i data-lucide="activity" class="w-7 h-7"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="text-lg font-black text-slate-800">${p.name}</h4>
                                <span class="bg-slate-100 text-slate-400 text-[8px] font-black px-2 py-1 rounded-md uppercase">${p.age}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-[11px] font-bold text-slate-400">${p.owner}</span>
                                <p class="text-[11px] text-slate-300">Updated: ${new Date(p.updated_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button onclick="openHistoryModalByPatientId(${p.id})" class="text-[10px] font-black text-red-600 uppercase italic hover:underline cursor-pointer">View Log</button>
                    </div>
                </div>`).join('');
            lucide.createIcons();
        }

        window.viewPetDetails = (id) => {
            const pet = data.patients.find(p => p.id === id);
            if (!pet) return;

            setView('Patient');
            const searchInput = document.getElementById('registry-search');
            if (searchInput) {
                searchInput.value = pet.patient_id;
                handleSearch(pet.patient_id);
            }
            openHistoryModalByPatientId(id);
        };

        function renderRegistry(type, search = '') {
            const listEl = document.getElementById('registry-list');
            const term = search ? search.toLowerCase() : '';

            if (type === 'Patient') {
                document.getElementById('registry-main-title').innerHTML = `<span class="text-4xl font-black uppercase tracking-tight italic text-slate-800"> PATIENT <span class="text-red-600">CLUSTER</span>`;
                document.getElementById('registry-headings').innerHTML = `<div class="col-span-3 text-[11px] font-black text-slate-800 uppercase tracking-widest">Record Profile</div><div class="col-span-6 text-[11px] font-black text-slate-800 uppercase tracking-widest">Patient Information</div><div class="col-span-3 text-right text-[11px] font-black text-slate-800 uppercase tracking-widest">Administrative</div>`;
                
                listEl.innerHTML = data.patients.map((p, i) => {
                    if (term && !p.name.toLowerCase().includes(term) && !p.patient_id.toLowerCase().includes(term) && !(p.owner && p.owner.toLowerCase().includes(term))) {
                        return ''; 
                    }

                    const isYoung = p.age.toLowerCase().includes('year') && parseInt(p.age) <= 1 || p.age.toLowerCase().includes('month');
                    const animalTypeDisplay = p.type && p.type.trim() !== '' ? p.type : 'UNKNOWN';
                    return `<div class="grid grid-cols-12 items-center py-8 px-6 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 rounded-2xl transition-all group"><div class="col-span-3"><h4 class="text-xl font-black text-slate-800 mb-1">${p.name}</h4><p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">${p.patient_id}</p></div><div class="col-span-6 flex flex-col gap-3"><div class="flex items-center gap-2"><span class="text-[9px] font-black bg-slate-800 text-white px-2 py-0.5 rounded uppercase">${animalTypeDisplay}</span><span class="${isYoung?'bg-[#10B981]':'bg-[#EF4444]'} text-white text-[9px] font-black px-2.5 py-1 rounded-lg">${p.age}</span><span class="text-base font-black text-slate-800 ml-2">${p.breed}</span></div><div class="flex items-center gap-6 text-[11px] text-slate-400"><span>${p.owner}</span> • <span>${p.birthday}</span></div></div><div class="col-span-3 flex justify-end gap-6 text-slate-200 group-hover:text-slate-300"><i onclick="openHistoryModal(${i})" data-lucide="history" class="w-4.5 h-4.5 cursor-pointer hover:text-slate-500"></i><i onclick="openModal('Patient', ${i})" data-lucide="edit-2" class="w-4.5 h-4.5 cursor-pointer hover:text-slate-800"></i><i onclick="deleteRecord('Patient', ${i})" data-lucide="trash-2" class="w-4.5 h-4.5 cursor-pointer hover:text-red-500"></i></div></div>`;
                }).join('');
            } else {
                document.getElementById('registry-main-title').innerHTML = `<span class="text-4xl font-black uppercase tracking-tight italic text-slate-800"> OWNER <span class="text-red-600">CLUSTER</span>`;
                document.getElementById('registry-headings').innerHTML = `<div class="col-span-4 text-[11px] font-black text-slate-800 uppercase tracking-widest">Record Profile</div><div class="col-span-5 text-center text-[11px] font-black text-slate-800 uppercase tracking-widest text-center">Contact Details</div><div class="col-span-3 text-right text-[11px] font-black text-slate-800 uppercase tracking-widest">Administrative</div>`;
                
                listEl.innerHTML = data.owners.map((o, i) => {
                    if (term && !o.name.toLowerCase().includes(term) && !o.email.toLowerCase().includes(term) && !o.phone.includes(term)) {
                        return ''; 
                    }

                    const ownerPets = data.patients.filter(p => p.owner === o.name);
                    let petsHTML = '';
                    if (ownerPets.length > 0) {
                        petsHTML = `<div class="mt-5 pt-5 border-t border-slate-100/50 flex gap-3 flex-wrap">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest w-full mb-1">Registered Pets (${ownerPets.length}):</p>
                            ${ownerPets.map(p => `
                                <div onclick="viewPetDetails(${p.id})" class="bg-white border border-slate-200 hover:border-red-300 hover:bg-red-50 hover:text-red-600 text-slate-600 cursor-pointer px-4 py-2 rounded-xl text-[11px] font-black flex items-center gap-2 shadow-sm transition-all group/pet">
                                    <i data-lucide="dog" class="w-3.5 h-3.5 text-slate-300 group-hover/pet:text-red-500"></i>
                                    <span>${p.name} <span class="opacity-60 font-bold ml-1">(${p.type || 'Pet'} • ${p.breed} • ${p.age})</span></span>
                                </div>
                            `).join('')}
                        </div>`;
                    }

                    return `<div class="flex flex-col py-8 px-6 border-b border-slate-50 last:border-0 hover:bg-slate-50/50 rounded-2xl group transition-all">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-4"><h4 class="text-xl font-black text-[#1e293b]">${o.name}</h4></div>
                            <div class="col-span-5 flex flex-col items-center"><p class="text-[11px] font-black text-[#334155] uppercase mb-1">${o.email}</p><p class="text-[11px] font-bold text-slate-400">${o.phone}</p></div>
                            <div class="col-span-3 flex justify-end gap-6 text-slate-200 group-hover:text-slate-300">
                                <i onclick="openModal('Owner', ${i})" data-lucide="edit-2" class="w-5 h-5 cursor-pointer hover:text-slate-800"></i>
                                <i onclick="deleteRecord('Owner', ${i})" data-lucide="trash-2" class="w-5 h-5 cursor-pointer hover:text-red-500"></i>
                            </div>
                        </div>
                        ${petsHTML}
                    </div>`;
                }).join('');
            }
            lucide.createIcons();
        }
    function renderInventory() {
            const grid = document.getElementById('inventory-grid');
            const low = data.inventory.filter(i => i.critical);

            const banner = document.getElementById('inventory-banner');
            if (low.length > 0) {
                banner.classList.remove('hidden');
                document.getElementById('inventory-banner-text').innerText = `Warning: ${low.length} item(s) running low!`;
            } else {
                banner.classList.add('hidden');
            }

            let inventoryHTML = data.inventory.map((item, idx) => `<div class="bg-white rounded-[2rem] p-8 border ${item.critical ? 'border-yellow-400 ring-2 ring-yellow-50' : 'border-slate-100'} shadow-sm relative transition-all group hover:shadow-md"><div class="flex justify-between items-start mb-6"><div class="flex items-center gap-4"><div class="w-12 h-12 ${item.critical ? 'bg-yellow-50 text-yellow-500' : 'bg-red-50 text-red-600'} rounded-2xl flex items-center justify-center"><i data-lucide="package" class="w-6 h-6"></i></div><div><h3 class="text-base font-black text-slate-800 uppercase leading-none mb-1">${item.name}</h3><span class="text-[9px] font-black bg-slate-100 text-slate-400 px-2 py-0.5 rounded uppercase">${item.category}</span></div></div><div class="flex gap-2 text-slate-200 group-hover:text-slate-400"><i onclick="openModal('Inventory', ${idx})" data-lucide="edit-2" class="w-4 h-4 cursor-pointer hover:text-slate-600"></i><i onclick="deleteRecord('Inventory', ${idx})" data-lucide="trash-2" class="w-4 h-4 cursor-pointer hover:text-red-500"></i></div></div><div class="flex justify-between items-end mb-6"><p class="label-style">Quantity:</p><div class="text-right leading-none"><span class="text-3xl font-black text-slate-800">${item.stock}</span><span class="text-[10px] font-bold text-slate-400 uppercase ml-1">${item.unit}</span></div></div>${item.critical?'<div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 flex items-center gap-2 mb-6"><i data-lucide="info" class="w-3 h-3 text-yellow-600"></i><p class="text-[10px] font-black text-yellow-700 uppercase">Critical Threshold</p></div>':''}<div class="space-y-3 pt-4 border-t border-slate-50"><div class="flex justify-between items-center"><span class="label-style">Threshold:</span><span class="text-[10px] font-black text-slate-500 uppercase">${item.threshold}</span></div><div class="flex justify-between items-center"><span class="label-style">Supplier:</span><span class="text-[10px] font-black text-slate-500 uppercase">${item.supplier}</span></div><div class="flex justify-between items-center"><span class="label-style">Restocked:</span><span class="text-[10px] font-black text-slate-500 uppercase">${item.restocked}</span></div></div></div>`).join('');
            
            const addCardHTML = `
                <div onclick="openModal('Inventory')" class="rounded-[2rem] border-2 border-dashed border-slate-300 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-slate-400 transition-all min-h-[250px] group opacity-70 hover:opacity-100">
                    <i data-lucide="plus" class="w-10 h-10 text-slate-400 mb-3 group-hover:scale-110 transition-transform"></i>
                    <p class="text-[13px] font-black text-slate-400 italic uppercase tracking-wide group-hover:text-slate-500">Add Inventory</p>
                </div>`;

            grid.innerHTML = inventoryHTML + addCardHTML;
            lucide.createIcons();
        }
        
        window.deleteRecord = async (type, i) => {
            if (confirm(`Are you sure you want to delete this ${type}?`)) {
                let idToDelete = null;

                if(type === 'Patient') idToDelete = data.patients[i].id;
                else if(type === 'Owner') idToDelete = data.owners[i].id;
                else if(type === 'Inventory') idToDelete = data.inventory[i].id;
                
                try {
                    const res = await apiCall('/api/delete', 'POST', { type: type, id: parseInt(idToDelete) });
                    if (res.success !== false) {
                        await loadData();
                        setView(currentType); 
                    } else {
                        alert("DATABASE ERROR: " + res.error);
                    }
                } catch (err) {
                    alert("Network Error during delete.");
                }
            }
        };

        window.openHistoryModalByPatientId = (id) => {
            const idx = data.patients.findIndex(p => p.id === id);
            if(idx !== -1) openHistoryModal(idx);
        }

        window.openHistoryModal = (i) => {
            historyActiveIdx = i; resetMedicalForm();
            const p = data.patients[i];
            document.getElementById('history-patient-name').innerText = p.name + " (" + p.patient_id + ")";
            
            const meds = data.inventory
                .filter(inv => inv.category === 'MEDICATION' || inv.category === 'FOOD')
                .sort((a, b) => a.name.localeCompare(b.name) || a.supplier.localeCompare(b.supplier));
                
            document.getElementById('med-options').innerHTML = meds.map(m => 
                `<option value="${m.name} [${m.supplier}] (Stock: ${m.stock})">`
            ).join('');

            renderMedicalRecords();
            document.getElementById('history-modal').classList.add('active'); lucide.createIcons();
        };

        window.addMedicalRecord = async (e) => {
            e.preventDefault(); 
            const p = data.patients[historyActiveIdx];
            
            const problem = document.getElementById('hist-problem').value;
            const action = document.getElementById('hist-action').value;
            const medInput = document.getElementById('hist-medicine-search').value;
            const qtyInput = document.getElementById('hist-quantity').value;

            if (!problem || !action) {
                alert("🛑 Missing Info: Please completely fill out the Problem and Action!");
                return;
            }

            let medicineString = medInput || 'None';
            let invId = null;
            let qty = 0;

            if (medInput) {
                const meds = data.inventory.filter(inv => inv.category === 'MEDICATION' || inv.category === 'FOOD');
                const matchedItem = meds.find(m => `${m.name} [${m.supplier}] (Stock: ${m.stock})` === medInput);
                
                if (matchedItem) {
                    if (!qtyInput || qtyInput < 1) {
                        alert("Please enter how many you are using in the 'Qty' box.");
                        return;
                    }
                    if (qtyInput > matchedItem.stock) {
                        alert(`Not enough stock! You only have ${matchedItem.stock} available.`);
                        return;
                    }
                    invId = matchedItem.id;
                    qty = parseInt(qtyInput);
                    medicineString = `${matchedItem.name} (${qty} ${matchedItem.unit})`;
                } else {

                    medicineString = medInput + (qtyInput ? ` (Qty: ${qtyInput})` : '');
                }
            }

            const record = { 
                patient_id: p.id, 
                problem: problem, 
                action: action, 
                medicine: medicineString, 
                inventory_id: invId, 
                quantity: qty,
                date: historyEditIdx !== null && p.records && p.records[historyEditIdx] ? p.records[historyEditIdx].date : new Date().toLocaleDateString() 
            };
            
            if (historyEditIdx !== null && p.records && p.records[historyEditIdx] && p.records[historyEditIdx].id) {
                record.id = p.records[historyEditIdx].id;
            }

            try {
                const res = await apiCall('/api/medical-records', 'POST', record);
                if(res.success !== false) {
                    await loadData(); 
                    resetMedicalForm(); 
                    renderMedicalRecords();
                    renderRegistry('Patient');
                } else {
                    alert("DATABASE ERROR: " + res.error);
                }
            } catch (err) {
                alert("SERVER CRASH! Please check your backend code.");
            }
        };

        function resetMedicalForm() { 
            document.getElementById('medical-form').reset(); 
            historyEditIdx = null; 
            document.getElementById('hist-form-title').innerText = "Add Clinic Entry"; 
            document.getElementById('hist-submit-btn').innerText = "Add Record"; 
            document.getElementById('hist-cancel-btn').classList.add('hidden'); 
        }

        window.editHistoryRecord = (i) => { 
            historyEditIdx = i; 
            const r = data.patients[historyActiveIdx].records[i]; 
            document.getElementById('hist-problem').value = r.problem; 
            document.getElementById('hist-action').value = r.action; 
            document.getElementById('hist-medicine-search').value = r.medicine; 
            document.getElementById('hist-quantity').value = ''; 
            document.getElementById('hist-form-title').innerText = "Update Clinic Entry"; 
            document.getElementById('hist-submit-btn').innerText = "Save Changes"; 
            document.getElementById('hist-cancel-btn').classList.remove('hidden'); 
        };
        window.closeHistoryModal = () => document.getElementById('history-modal').classList.remove('active');

        window.addMedicalRecord = async (e) => {
            e.preventDefault(); 
            const p = data.patients[historyActiveIdx];
            
            const problem = document.getElementById('hist-problem').value;
            const action = document.getElementById('hist-action').value;
            const medInput = document.getElementById('hist-medicine-search').value;
            const qtyInput = document.getElementById('hist-quantity').value;

            if (!problem || !action) {
                alert("🛑 Missing Info: Please completely fill out the Problem and Action!");
                return;
            }

            let medicineString = medInput || 'None';
            let invId = null;
            let qty = 0;

            if (medInput) {
                const meds = data.inventory.filter(inv => inv.category === 'MEDICATION' || inv.category === 'FOOD');
                const matchedItem = meds.find(m => `${m.name} [${m.supplier}] (Stock: ${m.stock})` === medInput);
                
                if (matchedItem) {
                    if (!qtyInput || qtyInput < 1) {
                        alert("Please enter how many you are using in the 'Qty' box.");
                        return;
                    }
                    if (qtyInput > matchedItem.stock) {
                        alert(`Not enough stock! You only have ${matchedItem.stock} available.`);
                        return;
                    }
                    invId = matchedItem.id;
                    qty = parseInt(qtyInput);
                    medicineString = `${matchedItem.name} (${qty} ${matchedItem.unit})`;
                } else {
                    medicineString = medInput + (qtyInput ? ` (Qty: ${qtyInput})` : '');
                }
            }

            const record = { 
                patient_id: p.id, 
                problem: problem, 
                action: action, 
                medicine: medicineString, 
                inventory_id: invId, 
                quantity: qty, 
                date: historyEditIdx !== null && p.records && p.records[historyEditIdx] ? p.records[historyEditIdx].date : new Date().toLocaleDateString() 
            };
            
            if (historyEditIdx !== null && p.records && p.records[historyEditIdx] && p.records[historyEditIdx].id) {
                record.id = p.records[historyEditIdx].id;
            }

            try {
                const res = await apiCall('/api/medical-records', 'POST', record);
                if(res.success !== false) {
                    await loadData(); 
                    resetMedicalForm(); 
                    renderMedicalRecords();
                    renderRegistry('Patient');
                } else {
                    alert("DATABASE ERROR: " + res.error);
                }
            } catch (err) {
                alert("SERVER CRASH! Please check your backend code.");
            }
        };

        function renderMedicalRecords() {
            const list = document.getElementById('medical-records-list'), records = data.patients[historyActiveIdx].records || [];
            list.innerHTML = records.length === 0 ? `<p class="text-center py-10 label-style">No clinical history found.</p>` : records.map((r, i) => `<div class="bg-white border border-slate-100 p-6 rounded-[1.5rem] shadow-sm group"><div class="flex justify-between mb-4"><span class="label-style text-red-600">${r.date}</span><div class="flex gap-3 opacity-0 group-hover:opacity-100 transition-opacity"><i onclick="editHistoryRecord(${i})" data-lucide="edit-2" class="w-4 h-4 text-slate-400 cursor-pointer hover:text-slate-800"></i><i onclick="deleteHistoryRecord(${i})" data-lucide="trash-2" class="w-4 h-4 text-slate-400 cursor-pointer hover:text-red-600"></i></div></div><div class="space-y-3"><div><p class="label-style text-[8px]">PROBLEM / COMPLAINT</p><p class="text-sm font-black text-slate-800 uppercase">${r.problem}</p></div><div><p class="label-style text-[8px]">CLINIC ACTION</p><p class="text-xs font-bold text-slate-500">${r.action}</p></div><div class="pt-2"><span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tight">Prescription: ${r.medicine}</span></div></div></div>`).join('');
            lucide.createIcons();
        }

        function resetMedicalForm() { 
            document.getElementById('medical-form').reset(); 
            historyEditIdx = null; 
            document.getElementById('hist-form-title').innerText = "Add Clinic Entry"; 
            document.getElementById('hist-submit-btn').innerText = "Add Record"; 
            document.getElementById('hist-cancel-btn').classList.add('hidden'); 
        }
        window.deleteHistoryRecord = async (i) => { 
            if (confirm("Remove record?")) { 
                const r = data.patients[historyActiveIdx].records[i];
                await apiCall('/api/medical-records/delete', 'POST', { id: r.id });
                await loadData(); renderMedicalRecords();
            } 
        };

        window.openModal = (type, i = null) => {
            editMode = i !== null; activeIdx = i; const fields = document.getElementById('modal-form-fields');
            document.getElementById('modal-title').innerText = `${editMode?'Edit':'Register'} ${type}`;
            
            if (type === 'Patient') {
                const p = editMode ? data.patients[i] : { name: '', breed: '', age: '', birthday: '', owner: '', type: '' };
                
                let ownerOptions = data.owners.map(o => `<option value="${o.name}" ${p.owner === o.name ? 'selected' : ''}>${o.name}</option>`).join('');
                if (data.owners.length === 0) {
                    ownerOptions = `<option value="" disabled selected>No Owners Available - Please add an Owner first</option>`;
                } else {
                    ownerOptions = `<option value="" disabled ${!p.owner ? 'selected' : ''}>Select an Owner</option>` + ownerOptions;
                }

                fields.innerHTML = `
                    <div class="space-y-1"><label class="label-style">Patient Name</label><input name="name" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${p.name}"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="label-style">Animal Type</label>
                            <input list="animal-types" name="type" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${p.type}" placeholder="e.g. DOG, CAT, BIRD">
                            <datalist id="animal-types">
                                <option value="DOG"></option>
                                <option value="CAT"></option>
                                <option value="BIRD"></option>
                                <option value="RABBIT"></option>
                                <option value="HAMSTER"></option>
                                <option value="TURTLE"></option>
                                <option value="FISH"></option>
                            </datalist>
                        </div>
                        <div class="space-y-1"><label class="label-style">Breed</label><input name="breed" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${p.breed}"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1"><label class="label-style">Age</label><input name="age" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${p.age}"></div>
                        <div class="space-y-1"><label class="label-style">Birthday</label><input type="date" name="birthday" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${p.birthday}"></div>
                    </div>
                    <div class="space-y-1"><label class="label-style">Owner</label><select name="owner" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700">${ownerOptions}</select></div>`;
            
            } else if (type === 'Owner') {
                const o = editMode ? data.owners[i] : { name: '', email: '', phone: '' };
                fields.innerHTML = `<div class="space-y-1"><label class="label-style">Full Name</label><input name="name" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${o.name}"></div><div class="space-y-1"><label class="label-style">Email</label><input name="email" type="email" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${o.email}"></div><div class="space-y-1"><label class="label-style">Phone</label><input name="phone" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${o.phone}"></div>`;
            } else if (type === 'Inventory') {
                const i_data = editMode ? data.inventory[i] : { name: '', category: 'MEDICATION', stock: '', unit: '', threshold: '', supplier: '', restocked: '' };
                fields.innerHTML = `<div class="space-y-1"><label class="label-style">Name</label><input name="name" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${i_data.name}"></div><div class="grid grid-cols-2 gap-4"><div class="space-y-1"><label class="label-style">Category</label><select name="category" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700"><option value="MEDICATION" ${i_data.category==='MEDICATION'?'selected':''}>Medication</option><option value="SUPPLIES" ${i_data.category==='SUPPLIES'?'selected':''}>Supplies</option><option value="FOOD" ${i_data.category==='FOOD'?'selected':''}>Food</option></select></div><div class="space-y-1"><label class="label-style">Quantity</label><input name="stock" type="number" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${i_data.stock}"></div></div><div class="grid grid-cols-2 gap-4"><div class="space-y-1"><label class="label-style">Unit</label><input name="unit" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${i_data.unit}"></div><div class="space-y-1"><label class="label-style">Threshold</label><input name="threshold" type="number" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${i_data.threshold}"></div></div><div class="space-y-1"><label class="label-style">Supplier</label><input name="supplier" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${i_data.supplier}"></div><div class="space-y-1"><label class="label-style">Restocked</label><input type="date" name="restocked" class="w-full bg-slate-50 rounded-2xl px-6 py-4 font-bold outline-none border-none text-slate-700" value="${i_data.restocked}"></div>`;
            }
            document.getElementById('global-modal').classList.add('active'); lucide.createIcons();
        };

        window.closeModal = () => document.getElementById('global-modal').classList.remove('active');

        window.handleFormSubmit = async (e) => {
            e.preventDefault(); 
            const formData = new FormData(e.target), entry = {}; 
            formData.forEach((v, k) => entry[k] = v);

            for (const [key, value] of Object.entries(entry)) {
                if (!value || value.trim() === '') {
                    alert(`🛑 Missing Info! Please make sure you fill out the empty box: [ ${key.toUpperCase()} ]`);
                    return; 
                }
            }

            if (editMode) {
                if(currentType === 'Patient') entry.id = parseInt(data.patients[activeIdx].id);
                else if(currentType === 'Owner') entry.id = parseInt(data.owners[activeIdx].id);
                else if(currentType === 'Inventory') entry.id = parseInt(data.inventory[activeIdx].id);
            }

            let backendModel = currentType;
            if (currentType === 'Inventory') backendModel = 'InventoryItem'; 

            if (currentType === 'Patient') {
                if(!editMode) entry.patient_id = `PAT-${Math.floor(Math.random() * 9000) + 1000}`; 
            } else if (currentType === 'Inventory') {
                entry.stock = parseInt(entry.stock); 
            }
            
            try {
                const res = await apiCall('/api/save', 'POST', { type: backendModel, payload: entry });
                if (res.success !== false) {
                    await loadData();
                    setView(currentType); 
                    closeModal();
                } else {
                    alert("DATABASE ERROR: " + res.error);
                }
            } catch (err) {
                alert("LARAVEL CRASHED! Check network tab for details.");
                console.error(err);
            }
        };

      window.openProfileModal = () => { 
            document.getElementById('prof-name').value = currentUser.name; 
            document.getElementById('prof-user').value = currentUser.username || ''; 
            document.getElementById('prof-email').value = currentUser.email; 
            document.getElementById('prof-pass').value = ''; 
            document.getElementById('prof-pass-confirm').value = ''; 
            document.getElementById('prof-error').classList.add('hidden');
            document.getElementById('profile-modal').classList.add('active'); 
            lucide.createIcons(); 
        };

        window.closeProfileModal = () => { 
            document.getElementById('profile-modal').classList.remove('active'); 
        };

        window.handleProfileUpdate = async (e) => {
            e.preventDefault();
            const n = document.getElementById('prof-name').value;
            const u = document.getElementById('prof-user').value;
            const em = document.getElementById('prof-email').value;
            const p = document.getElementById('prof-pass').value;
            const pc = document.getElementById('prof-pass-confirm').value;
            const err = document.getElementById('prof-error');

            if (p && p !== pc) {
                err.innerText = "Passwords do not match!";
                err.classList.remove('hidden');
                return;
            }

            try {
                const res = await apiCall('/api/profile/update', 'POST', { name: n, username: u, email: em, password: p });
                if (res.success) {
                    currentUser = res.user;
                    document.getElementById('current-user-display').innerText = currentUser.name;
                    alert("Profile updated successfully!");
                    closeProfileModal();
                } else {
                    err.innerText = res.error || "Update failed. Username or Email might be taken.";
                    err.classList.remove('hidden');
                }
            } catch (error) {
                err.innerText = "Server error during update.";
                err.classList.remove('hidden');
            }
        };

        window.addEventListener('DOMContentLoaded', async () => {
            try {

                const res = await apiCall('/api/data', 'GET');
                
                if (res && res.user) {
                    currentUser = res.user;
                    data = res; 
                    
                    document.getElementById('login-view').classList.add('hidden-view');
                    document.getElementById('dashboard-view').classList.remove('hidden-view');
                    

                    setView('Dashboard');
                    lucide.createIcons();
                }
            } catch (err) {

                console.log("No active session. Waiting for user to log in.");
            }
        });
    </script>
</body>
</html>