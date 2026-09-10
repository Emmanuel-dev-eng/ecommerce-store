<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | ShopName</title>
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
            <a href="account.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="user" class="w-5 h-5"></i><span class="text-sm font-medium">My Account</span>
            </a>
            <a href="orders.php" class="flex items-center gap-3 py-3 px-2 rounded-lg bg-gray-100 text-gray-900">
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

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <h1 class="text-2xl font-bold text-gray-900 mb-6">My Orders</h1>

    <!-- ===================== STATUS FILTER TABS ===================== -->
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-1">
        <button class="status-tab whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold bg-black text-white" data-status="all">
            All Orders
        </button>
        <button class="status-tab whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-300 text-gray-600 hover:border-black" data-status="pending">
            Pending
        </button>
        <button class="status-tab whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-300 text-gray-600 hover:border-black" data-status="processing">
            Processing
        </button>
        <button class="status-tab whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-300 text-gray-600 hover:border-black" data-status="shipped">
            Shipped
        </button>
        <button class="status-tab whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold bg-white border border-gray-300 text-gray-600 hover:border-black" data-status="delivered">
            Delivered
        </button>
    </div>

    <!-- ===================== ORDERS LIST ===================== -->
    <div id="ordersList" class="space-y-4">
        <!-- Orders injected by JS -->
    </div>

    <div id="noOrdersMsg" class="hidden text-center py-16">
        <i data-lucide="package-x" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
        <p class="text-gray-500">No orders in this category.</p>
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

    // ---- Sample order history ----
    // NOTE: this will be replaced by a real PHP loop pulling rows from
    // the `orders` table (WHERE user_id = current session user) in the
    // backend phase.
    const orders = [
        {
            number: 'ORD-482913', date: 'Aug 20, 2026', status: 'shipped', total: 149.98, itemCount: 2,
            image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=200&q=80'
        },
        {
            number: 'ORD-471820', date: 'Aug 10, 2026', status: 'delivered', total: 74.99, itemCount: 1,
            image: 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=200&q=80'
        },
        {
            number: 'ORD-465301', date: 'Jul 28, 2026', status: 'delivered', total: 229.97, itemCount: 3,
            image: 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=200&q=80'
        },
        {
            number: 'ORD-459102', date: 'Jul 15, 2026', status: 'processing', total: 59.99, itemCount: 1,
            image: 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=200&q=80'
        },
        {
            number: 'ORD-450221', date: 'Jul 2, 2026', status: 'pending', total: 129.99, itemCount: 1,
            image: 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=200&q=80'
        }
    ];

    const statusStyles = {
        pending:    'bg-gray-100 text-gray-600',
        processing: 'bg-yellow-50 text-yellow-700',
        shipped:    'bg-blue-50 text-blue-600',
        delivered:  'bg-green-50 text-green-600'
    };

    const statusLabels = {
        pending: 'Pending', processing: 'Processing', shipped: 'Shipped', delivered: 'Delivered'
    };

    const ordersList = document.getElementById('ordersList');
    const noOrdersMsg = document.getElementById('noOrdersMsg');

    function renderOrders(filter) {
        ordersList.innerHTML = '';
        const filtered = filter === 'all' ? orders : orders.filter(o => o.status === filter);

        if (filtered.length === 0) {
            noOrdersMsg.classList.remove('hidden');
            return;
        }
        noOrdersMsg.classList.add('hidden');

        filtered.forEach(order => {
            const card = document.createElement('a');
            card.href = `order-detail.php?order=${order.number}`;
            card.className = 'block bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 hover:border-gray-300 transition';
            card.innerHTML = `
                <div class="flex items-center gap-4">
                    <img src="${order.image}" alt="Order ${order.number}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <p class="text-sm font-semibold text-gray-900">#${order.number}</p>
                            <span class="text-[11px] font-semibold px-2.5 py-1 rounded-full flex-shrink-0 ${statusStyles[order.status]}">
                                ${statusLabels[order.status]}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">${order.date} · ${order.itemCount} item${order.itemCount > 1 ? 's' : ''}</p>
                        <p class="text-sm font-bold text-gray-900 mt-1">$${order.total.toFixed(2)}</p>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                </div>
            `;
            ordersList.appendChild(card);
        });

        lucide.createIcons();
    }

    // ---- Filter tab clicks ----
    document.querySelectorAll('.status-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.status-tab').forEach(t => {
                t.classList.remove('bg-black', 'text-white');
                t.classList.add('bg-white', 'border', 'border-gray-300', 'text-gray-600');
            });
            tab.classList.add('bg-black', 'text-white');
            tab.classList.remove('bg-white', 'border', 'border-gray-300', 'text-gray-600');
            renderOrders(tab.dataset.status);
        });
    });

    renderOrders('all');

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