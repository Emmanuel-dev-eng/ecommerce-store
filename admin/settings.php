<?php require '../config/admin_auth_check.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | ShopName Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<div class="flex min-h-screen">

    <!-- ===================== SIDEBAR (desktop) ===================== -->
    <aside class="hidden lg:flex lg:flex-col w-64 bg-gray-900 text-gray-300 flex-shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-gray-800">
            <span class="text-white font-bold text-lg">ShopName <span class="text-gray-500 font-normal text-sm">Admin</span></span>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
            </a>
            <a href="products.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="package" class="w-4 h-4"></i> Products
            </a>
            <a href="categories.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="tag" class="w-4 h-4"></i> Categories
            </a>
            <a href="orders.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i> Orders
            </a>
            <a href="customers.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="users" class="w-4 h-4"></i> Customers
            </a>
            <a href="settings.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-gray-800 text-white text-sm font-medium">
                <i data-lucide="settings" class="w-4 h-4"></i> Settings
            </a>
        </nav>
        <div class="p-3 border-t border-gray-800">
            <a href="../logout.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium text-red-400">
                <i data-lucide="log-out" class="w-4 h-4"></i> Log Out
            </a>
        </div>
    </aside>

    <!-- ===================== MAIN AREA ===================== -->
    <div class="flex-1 flex flex-col min-w-0">

        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <button id="adminMenuToggle" class="lg:hidden text-gray-700">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="text-lg font-bold text-gray-900">Settings</h1>
            </div>
            <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">A</div>
        </header>

        <!-- ===================== MOBILE SIDEBAR DRAWER ===================== -->
        <div id="adminMobileDrawer" class="hidden fixed inset-0 z-50 lg:hidden">
            <div id="adminDrawerOverlay" class="absolute inset-0 bg-black/50"></div>
            <div class="absolute left-0 top-0 bottom-0 w-64 bg-gray-900 text-gray-300 flex flex-col">
                <div class="h-16 flex items-center justify-between px-4 border-b border-gray-800">
                    <span class="text-white font-bold">ShopName Admin</span>
                    <button id="adminDrawerClose" class="text-gray-400">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <nav class="flex-1 px-3 py-4 space-y-1">
                    <a href="dashboard.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                    </a>
                    <a href="products.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="package" class="w-4 h-4"></i> Products
                    </a>
                    <a href="categories.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="tag" class="w-4 h-4"></i> Categories
                    </a>
                    <a href="orders.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i> Orders
                    </a>
                    <a href="customers.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="users" class="w-4 h-4"></i> Customers
                    </a>
                    <a href="settings.php" class="flex items-center gap-3 px-3 py-3 rounded-lg bg-gray-800 text-white text-sm font-medium">
                        <i data-lucide="settings" class="w-4 h-4"></i> Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- ===================== PAGE CONTENT ===================== -->
        <main class="flex-1 p-4 sm:p-6">

            <div class="grid lg:grid-cols-4 gap-6">

                <!-- Settings tabs -->
                <aside class="lg:col-span-1">
                    <nav class="flex lg:flex-col gap-1 overflow-x-auto lg:overflow-visible pb-2 lg:pb-0">
                        <button class="settings-tab flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap bg-black text-white" data-tab="store">
                            <i data-lucide="store" class="w-4 h-4"></i> Store Info
                        </button>
                        <button class="settings-tab flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap text-gray-700 hover:bg-gray-100" data-tab="shipping">
                            <i data-lucide="truck" class="w-4 h-4"></i> Shipping
                        </button>
                        <button class="settings-tab flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap text-gray-700 hover:bg-gray-100" data-tab="payment">
                            <i data-lucide="credit-card" class="w-4 h-4"></i> Payment
                        </button>
                        <button class="settings-tab flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap text-gray-700 hover:bg-gray-100" data-tab="account">
                            <i data-lucide="user-cog" class="w-4 h-4"></i> Admin Account
                        </button>
                    </nav>
                </aside>

                <!-- Tab content -->
                <div class="lg:col-span-3">

                    <!-- Store Info tab -->
                    <div id="tab-store" class="settings-content">
                        <div class="bg-white rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Store Information</h2>
                            <form class="settings-form space-y-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Store Name</label>
                                    <input type="text" value="ShopName" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Store Email</label>
                                    <input type="email" value="support@shopname.com" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Store Phone</label>
                                    <input type="tel" value="+234 800 000 0000" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Store Address</label>
                                    <textarea rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black resize-none">12 Palm Avenue, Lekki, Lagos, Nigeria</textarea>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Currency</label>
                                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                        <option>USD ($)</option>
                                        <option>NGN (₦)</option>
                                        <option>GBP (£)</option>
                                        <option>EUR (€)</option>
                                    </select>
                                </div>
                                <button type="submit" class="save-btn bg-black text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-800">
                                    Save Changes
                                </button>
                                <span class="saved-msg hidden text-xs text-green-600 ml-3">✓ Saved</span>
                            </form>
                        </div>
                    </div>

                    <!-- Shipping tab -->
                    <div id="tab-shipping" class="settings-content hidden">
                        <div class="bg-white rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Shipping Options</h2>
                            <form class="settings-form space-y-4">
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Standard Shipping Cost ($)</label>
                                        <input type="number" step="0.01" value="0.00" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                    </div>
                                    <div>
                                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Express Shipping Cost ($)</label>
                                        <input type="number" step="0.01" value="14.99" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Free Shipping Threshold ($)</label>
                                    <input type="number" step="0.01" value="75.00" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                    <p class="text-xs text-gray-400 mt-1">Orders above this amount qualify for free standard shipping.</p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Tax Rate (%)</label>
                                    <input type="number" step="0.1" value="7.5" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                </div>
                                <button type="submit" class="save-btn bg-black text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-800">
                                    Save Changes
                                </button>
                                <span class="saved-msg hidden text-xs text-green-600 ml-3">✓ Saved</span>
                            </form>
                        </div>
                    </div>

                    <!-- Payment tab -->
                    <div id="tab-payment" class="settings-content hidden">
                        <div class="bg-white rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Payment Methods</h2>
                            <p class="text-xs text-gray-500 mb-4">
                                Connect a real payment gateway once the backend is wired up. For now, these toggles just show intent.
                            </p>
                            <div class="space-y-3">
                               <label class="flex items-center justify-between border border-gray-200 rounded-xl p-4">
    <span class="flex items-center gap-3">
        <i data-lucide="credit-card" class="w-5 h-5 text-gray-600"></i>
        <span class="text-sm font-medium text-gray-900">Stripe</span>
    </span>
    <input type="checkbox" class="payment-toggle w-4 h-4 rounded border-gray-300">
</label>
<label class="flex items-center justify-between border border-gray-200 rounded-xl p-4">
    <span class="flex items-center gap-3">
        <i data-lucide="banknote" class="w-5 h-5 text-gray-600"></i>
        <span class="text-sm font-medium text-gray-900">Paystack</span>
    </span>
    <input type="checkbox" class="payment-toggle w-4 h-4 rounded border-gray-300" checked>
</label>
                                <label class="flex items-center justify-between border border-gray-200 rounded-xl p-4">
                                    <span class="flex items-center gap-3">
                                        <i data-lucide="wallet" class="w-5 h-5 text-gray-600"></i>
                                        <span class="text-sm font-medium text-gray-900">Cash on Delivery</span>
                                    </span>
                                    <input type="checkbox" class="payment-toggle w-4 h-4 rounded border-gray-300" checked>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Account tab -->
                    <div id="tab-account" class="settings-content hidden">
                        <div class="bg-white rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-sm font-bold text-gray-900 mb-4">Admin Account</h2>
                            <form class="settings-form space-y-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Admin Name</label>
                                    <input type="text" value="Admin User" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Admin Email</label>
                                    <input type="email" value="admin@shopname.com" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                </div>
                                <div class="border-t border-gray-100 pt-4">
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">New Password</label>
                                    <input type="password" placeholder="Leave blank to keep current password" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                </div>
                                <button type="submit" class="save-btn bg-black text-white text-sm font-semibold px-6 py-2.5 rounded-lg hover:bg-gray-800">
                                    Save Changes
                                </button>
                                <span class="saved-msg hidden text-xs text-green-600 ml-3">✓ Saved</span>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

<script>
    lucide.createIcons();

    const drawer = document.getElementById('adminMobileDrawer');
    document.getElementById('adminMenuToggle').addEventListener('click', () => drawer.classList.remove('hidden'));
    document.getElementById('adminDrawerClose').addEventListener('click', () => drawer.classList.add('hidden'));
    document.getElementById('adminDrawerOverlay').addEventListener('click', () => drawer.classList.add('hidden'));

    // ---- Tab switching ----
    const tabs = document.querySelectorAll('.settings-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('bg-black', 'text-white');
                t.classList.add('text-gray-700');
            });
            tab.classList.add('bg-black', 'text-white');
            tab.classList.remove('text-gray-700');

            document.querySelectorAll('.settings-content').forEach(c => c.classList.add('hidden'));
            document.getElementById('tab-' + tab.dataset.tab).classList.remove('hidden');
        });
    });

    // ---- Save forms (each tab's form independently) ----
    document.querySelectorAll('.settings-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            // NOTE: real save runs an UPDATE query against a `settings`
            // table (or the `users` table for the admin account tab)
            // via PHP in the backend phase. This confirms the save
            // interaction and feedback work correctly.
            const msg = form.querySelector('.saved-msg');
            msg.classList.remove('hidden');
            setTimeout(() => msg.classList.add('hidden'), 2500);
        });
    });

    // ---- Payment method toggles ----
    document.querySelectorAll('.payment-toggle').forEach(toggle => {
        toggle.addEventListener('change', () => {
            // NOTE: real toggle updates which gateways are active for
            // checkout via the backend phase.
        });
    });
</script>

</body>
</html>