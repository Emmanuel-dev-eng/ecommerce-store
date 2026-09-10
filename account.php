<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER ===================== -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight">ShopName</a>

            <nav class="hidden md:flex items-center gap-8">
                <a href="index.php" class="text-sm font-medium text-gray-700 hover:text-black">Home</a>
                <a href="shop.php" class="text-sm font-medium text-gray-700 hover:text-black">Shop</a>
                <a href="about.php" class="text-sm font-medium text-gray-700 hover:text-black">About</a>
                <a href="contact.php" class="text-sm font-medium text-gray-700 hover:text-black">Contact</a>
            </nav>

            <div class="flex items-center gap-4">
                <button id="searchToggle" class="text-gray-700 hover:text-black">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
                <a href="cart.php" class="relative text-gray-700 hover:text-black">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span class="absolute -top-2 -right-2 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">2</span>
                </a>
                <a href="account.php" class="hidden md:block text-gray-900">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </a>
                <button id="menuToggle" class="md:hidden text-gray-700 hover:text-black">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <div id="searchBar" class="hidden pb-4">
            <div class="relative">
                <input type="text" placeholder="Search products..."
                    class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100">
        <nav class="flex flex-col px-4 py-4 gap-1">
            <a href="index.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="home" class="w-5 h-5"></i><span class="text-sm font-medium">Home</span>
            </a>
            <a href="shop.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i><span class="text-sm font-medium">Shop</span>
            </a>
            <a href="account.php" class="flex items-center gap-3 py-3 px-2 rounded-lg bg-gray-100 text-gray-900">
                <i data-lucide="user" class="w-5 h-5"></i><span class="text-sm font-medium">My Account</span>
            </a>
            <a href="orders.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="package" class="w-5 h-5"></i><span class="text-sm font-medium">My Orders</span>
            </a>
            <a href="about.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="info" class="w-5 h-5"></i><span class="text-sm font-medium">About</span>
            </a>
            <a href="contact.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="mail" class="w-5 h-5"></i><span class="text-sm font-medium">Contact</span>
            </a>
        </nav>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- ===================== PROFILE HEADER ===================== -->
    <div class="flex items-center gap-4 mb-8">
        <div class="w-16 h-16 rounded-full bg-gray-900 text-white flex items-center justify-center text-xl font-bold flex-shrink-0">
            JD
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Jane Doe</h1>
            <p class="text-sm text-gray-500">jane.doe@example.com</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-4 gap-8">

        <!-- ===================== SIDEBAR NAV (tabs) ===================== -->
        <aside class="lg:col-span-1">
            <nav class="flex lg:flex-col gap-1 overflow-x-auto lg:overflow-visible pb-2 lg:pb-0">
                <button class="account-tab flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap bg-black text-white" data-tab="overview">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Overview
                </button>
                <button class="account-tab flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap text-gray-700 hover:bg-gray-100" data-tab="profile">
                    <i data-lucide="user" class="w-4 h-4"></i> Profile Info
                </button>
                <button class="account-tab flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap text-gray-700 hover:bg-gray-100" data-tab="address">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> Saved Address
                </button>
                <a href="orders.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap text-gray-700 hover:bg-gray-100">
                    <i data-lucide="package" class="w-4 h-4"></i> My Orders
                </a>
                <button id="logoutBtn" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap text-red-600 hover:bg-red-50">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Log Out
                </button>
            </nav>
        </aside>

        <!-- ===================== TAB CONTENT ===================== -->
        <div class="lg:col-span-3">

            <!-- Overview tab -->
            <div id="tab-overview" class="tab-content space-y-6">
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs text-gray-500 mb-1">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900">7</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs text-gray-500 mb-1">Wishlist Items</p>
                        <p class="text-2xl font-bold text-gray-900">4</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-gray-200 p-5">
                        <p class="text-xs text-gray-500 mb-1">Reward Points</p>
                        <p class="text-2xl font-bold text-gray-900">320</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-900">Recent Order</h2>
                        <a href="orders.php" class="text-xs font-medium text-gray-500 hover:text-black">View all</a>
                    </div>
                    <div class="flex items-center gap-4">
                        <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&q=80" class="w-14 h-14 rounded-lg object-cover">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Order #ORD-482913</p>
                            <p class="text-xs text-gray-500">2 items · Placed Aug 20, 2026</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-blue-50 text-blue-600">Shipped</span>
                    </div>
                </div>
            </div>

            <!-- Profile tab -->
            <div id="tab-profile" class="tab-content hidden">
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h2 class="text-sm font-bold text-gray-900 mb-4">Profile Information</h2>
                    <form id="profileForm" class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Full Name</label>
                                <input type="text" value="Jane Doe"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Email Address</label>
                                <input type="email" value="jane.doe@example.com"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Phone Number</label>
                                <input type="tel" value="+234 800 000 0000"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Date of Birth</label>
                                <input type="date"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                        </div>
                        <button type="submit" class="bg-black text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-800">
                            Save Changes
                        </button>
                        <span id="profileSavedMsg" class="hidden text-xs text-green-600 ml-3">✓ Saved</span>
                    </form>
                </div>
            </div>

            <!-- Address tab -->
            <div id="tab-address" class="tab-content hidden">
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-bold text-gray-900">Saved Address</h2>
                        <button class="text-xs font-medium text-gray-900 hover:underline">+ Add New</button>
                    </div>
                    <div class="border border-gray-200 rounded-xl p-4 flex items-start justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                Home <span class="text-[10px] bg-gray-900 text-white px-2 py-0.5 rounded-full">Default</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Jane Doe · +234 800 000 0000</p>
                            <p class="text-xs text-gray-500">12 Palm Avenue, Lekki, Lagos, Nigeria</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="text-xs text-gray-500 hover:text-black">Edit</button>
                            <button class="text-xs text-red-500 hover:text-red-700">Remove</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
    lucide.createIcons();

    document.getElementById('menuToggle').addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
    document.getElementById('searchToggle').addEventListener('click', () => {
        document.getElementById('searchBar').classList.toggle('hidden');
    });

    // ---- Tab switching ----
    const tabs = document.querySelectorAll('.account-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('bg-black', 'text-white'));
            tabs.forEach(t => t.classList.add('text-gray-700'));
            tab.classList.add('bg-black', 'text-white');
            tab.classList.remove('text-gray-700');

            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.getElementById('tab-' + tab.dataset.tab).classList.remove('hidden');
        });
    });

    // ---- Profile save (demo) ----
    document.getElementById('profileForm').addEventListener('submit', (e) => {
        e.preventDefault();
        // NOTE: real save happens via PHP UPDATE query to the `users`
        // table in the backend phase. This confirms the UI feedback works.
        const msg = document.getElementById('profileSavedMsg');
        msg.classList.remove('hidden');
        setTimeout(() => msg.classList.add('hidden'), 2500);
    });

    // ---- Logout (demo) ----
    document.getElementById('logoutBtn').addEventListener('click', () => {
        // NOTE: real logout destroys the PHP session in the backend phase.
        window.location.href = 'login.php';
    });
    

     // ---- Connect header search to the real search page ----
const searchInputField = document.querySelector('#searchBar input');
searchInputField.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        const term = searchInputField.value.trim();
        if (term) {
            window.location.href = `search.php?q=${encodeURIComponent(term)}`;
        }
    }
});
</script>

</body>
</html>