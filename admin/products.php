<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | ShopName Admin</title>
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
            <a href="products.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-gray-800 text-white text-sm font-medium">
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
            <a href="settings.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium">
                <i data-lucide="settings" class="w-4 h-4"></i> Settings
            </a>
        </nav>
        <div class="p-3 border-t border-gray-800">
            <a href="../login.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 text-sm font-medium text-red-400">
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
                <h1 class="text-lg font-bold text-gray-900">Products</h1>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative text-gray-500 hover:text-gray-900">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="w-8 h-8 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">A</div>
            </div>
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
                    <a href="products.php" class="flex items-center gap-3 px-3 py-3 rounded-lg bg-gray-800 text-white text-sm font-medium">
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
                    <a href="settings.php" class="flex items-center gap-3 px-3 py-3 rounded-lg hover:bg-gray-800 text-sm font-medium">
                        <i data-lucide="settings" class="w-4 h-4"></i> Settings
                    </a>
                </nav>
            </div>
        </div>

        <!-- ===================== PAGE CONTENT ===================== -->
        <main class="flex-1 p-4 sm:p-6">

            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div class="relative w-full sm:max-w-xs">
                    <input type="text" id="productSearch" placeholder="Search products..."
                        class="w-full border border-gray-300 rounded-lg py-2 pl-9 pr-3 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
                <a href="product-edit.php" class="inline-flex items-center justify-center gap-2 bg-black text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:bg-gray-800">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Product
                </a>
            </div>

            <!-- Products table -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-400 text-xs border-b border-gray-100 bg-gray-50">
                                <th class="py-3 px-4 font-medium">Product</th>
                                <th class="py-3 px-4 font-medium">Category</th>
                                <th class="py-3 px-4 font-medium">Price</th>
                                <th class="py-3 px-4 font-medium">Stock</th>
                                <th class="py-3 px-4 font-medium">Status</th>
                                <th class="py-3 px-4 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody" class="divide-y divide-gray-50">
                            <!-- Injected by JS -->
                        </tbody>
                    </table>
                </div>
                <p id="noProductsMsg" class="hidden text-center text-sm text-gray-400 py-10">No products match your search.</p>
            </div>

        </main>
    </div>
</div>

<!-- ===================== DELETE CONFIRMATION MODAL ===================== -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div id="deleteModalOverlay" class="absolute inset-0 bg-black/50"></div>
    <div class="relative bg-white rounded-2xl p-6 w-full max-w-sm">
        <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="trash-2" class="w-5 h-5 text-red-500"></i>
        </div>
        <h2 class="text-base font-bold text-gray-900 mb-1">Delete Product?</h2>
        <p id="deleteModalText" class="text-sm text-gray-500 mb-6">Are you sure you want to delete this product? This cannot be undone.</p>
        <div class="flex gap-3">
            <button id="deleteCancelBtn" class="flex-1 border border-gray-300 text-gray-700 text-sm font-semibold py-2.5 rounded-lg hover:bg-gray-50">
                Cancel
            </button>
            <button id="deleteConfirmBtn" class="flex-1 bg-red-600 text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-red-700">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- ===================== TOAST ===================== -->
<div id="toast" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-sm px-4 py-3 rounded-lg shadow-lg z-50 items-center gap-2">
    <i data-lucide="check-circle" class="w-4 h-4 text-green-400"></i>
    <span id="toastText">Product deleted.</span>
</div>

<script>
    lucide.createIcons();

    const drawer = document.getElementById('adminMobileDrawer');
    document.getElementById('adminMenuToggle').addEventListener('click', () => drawer.classList.remove('hidden'));
    document.getElementById('adminDrawerClose').addEventListener('click', () => drawer.classList.add('hidden'));
    document.getElementById('adminDrawerOverlay').addEventListener('click', () => drawer.classList.add('hidden'));

    // ---- Sample product data ----
    // NOTE: this becomes `SELECT * FROM products` joined with
    // `product_variants` (for stock) once PHP + MySQL are connected.
    let products = [
        { id: 1, name: 'Classic White Sneakers', category: 'Shoes', price: 59.99, stock: 14, active: true, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=100&q=80' },
        { id: 2, name: 'Oversized Denim Jacket', category: 'Men', price: 89.99, stock: 8, active: true, image: 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=100&q=80' },
        { id: 3, name: 'Leather Crossbody Bag', category: 'Accessories', price: 74.99, stock: 0, active: true, image: 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=100&q=80' },
        { id: 4, name: 'Minimalist Wrist Watch', category: 'Accessories', price: 129.99, stock: 22, active: true, image: 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=100&q=80' },
        { id: 5, name: 'Wool Blend Overcoat', category: 'Women', price: 149.99, stock: 3, active: false, image: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&q=80' },
        { id: 6, name: 'Running Sneakers', category: 'Shoes', price: 99.99, stock: 17, active: true, image: 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=100&q=80' }
    ];

    const tbody = document.getElementById('productsTableBody');
    const noProductsMsg = document.getElementById('noProductsMsg');
    let productToDelete = null;

    function renderProducts(filterTerm = '') {
        tbody.innerHTML = '';
        const filtered = products.filter(p => p.name.toLowerCase().includes(filterTerm.toLowerCase()));

        if (filtered.length === 0) {
            noProductsMsg.classList.remove('hidden');
        } else {
            noProductsMsg.classList.add('hidden');
        }

        filtered.forEach(p => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.innerHTML = `
                <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                        <img src="${p.image}" alt="${p.name}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                        <span class="font-medium text-gray-900">${p.name}</span>
                    </div>
                </td>
                <td class="py-3 px-4 text-gray-600">${p.category}</td>
                <td class="py-3 px-4 font-medium text-gray-900">$${p.price.toFixed(2)}</td>
                <td class="py-3 px-4">
                    <span class="${p.stock === 0 ? 'text-red-500' : p.stock < 5 ? 'text-orange-500' : 'text-gray-600'}">
                        ${p.stock === 0 ? 'Out of stock' : p.stock}
                    </span>
                </td>
                <td class="py-3 px-4">
                    <span class="text-[11px] font-semibold px-2 py-1 rounded-full ${p.active ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500'}">
                        ${p.active ? 'Active' : 'Hidden'}
                    </span>
                </td>
                <td class="py-3 px-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="product-edit.php?id=${p.id}" class="p-2 text-gray-500 hover:text-black hover:bg-gray-100 rounded-lg">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </a>
                        <button class="delete-btn p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg" data-id="${p.id}" data-name="${p.name}">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });

        lucide.createIcons();
        attachDeleteEvents();
    }

    function attachDeleteEvents() {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                productToDelete = parseInt(btn.dataset.id);
                document.getElementById('deleteModalText').textContent =
                    `Are you sure you want to delete "${btn.dataset.name}"? This cannot be undone.`;
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteModal').classList.add('flex');
            });
        });
    }

    // ---- Live search ----
    document.getElementById('productSearch').addEventListener('input', (e) => {
        renderProducts(e.target.value);
    });

    // ---- Delete modal logic ----
    const deleteModal = document.getElementById('deleteModal');
    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
        productToDelete = null;
    }
    document.getElementById('deleteCancelBtn').addEventListener('click', closeDeleteModal);
    document.getElementById('deleteModalOverlay').addEventListener('click', closeDeleteModal);

    document.getElementById('deleteConfirmBtn').addEventListener('click', () => {
        if (productToDelete !== null) {
            // NOTE: real deletion runs `DELETE FROM products WHERE id = ?`
            // via PHP in the backend phase. This removes it from the live
            // table right now, proving the interaction works end-to-end.
            products = products.filter(p => p.id !== productToDelete);
            renderProducts(document.getElementById('productSearch').value);
            showToast('Product deleted successfully.');
        }
        closeDeleteModal();
    });

    // ---- Toast ----
    function showToast(text) {
        const toast = document.getElementById('toast');
        document.getElementById('toastText').textContent = text;
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
        }, 3000);
    }

    renderProducts();
</script>

</body>
</html>