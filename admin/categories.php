<?php?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories | ShopName Admin</title>
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
            <a href="categories.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-gray-800 text-white text-sm font-medium">
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
                <h1 class="text-lg font-bold text-gray-900">Categories</h1>
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
                    <a href="categories.php" class="flex items-center gap-3 px-3 py-3 rounded-lg bg-gray-800 text-white text-sm font-medium">
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

            <div class="grid lg:grid-cols-3 gap-6">

                <!-- Add category form -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24">
                        <h2 id="formTitle" class="text-sm font-bold text-gray-900 mb-4">Add New Category</h2>
                        <form id="categoryForm" class="space-y-4">
                            <input type="hidden" id="editingId" value="">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Category Name</label>
                                <input type="text" id="categoryName" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                <p id="categoryNameError" class="hidden text-xs text-red-500 mt-1">Category name is required.</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Parent Category (optional)</label>
                                <select id="parentCategory" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                    <option value="">None (top-level)</option>
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" id="submitCategoryBtn" class="flex-1 bg-black text-white text-sm font-semibold py-2.5 rounded-lg hover:bg-gray-800">
                                    Add Category
                                </button>
                                <button type="button" id="cancelEditBtn" class="hidden px-4 border border-gray-300 text-sm font-semibold rounded-lg hover:bg-gray-50">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Category list -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-400 text-xs border-b border-gray-100 bg-gray-50">
                                    <th class="py-3 px-4 font-medium">Name</th>
                                    <th class="py-3 px-4 font-medium">Parent</th>
                                    <th class="py-3 px-4 font-medium">Products</th>
                                    <th class="py-3 px-4 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="categoriesTableBody" class="divide-y divide-gray-50">
                                <!-- Injected by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
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
        <h2 class="text-base font-bold text-gray-900 mb-1">Delete Category?</h2>
        <p id="deleteModalText" class="text-sm text-gray-500 mb-6"></p>
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
    <span id="toastText">Saved.</span>
</div>

<script>
    lucide.createIcons();

    const drawer = document.getElementById('adminMobileDrawer');
    document.getElementById('adminMenuToggle').addEventListener('click', () => drawer.classList.remove('hidden'));
    document.getElementById('adminDrawerClose').addEventListener('click', () => drawer.classList.add('hidden'));
    document.getElementById('adminDrawerOverlay').addEventListener('click', () => drawer.classList.add('hidden'));

    // ---- Sample category data ----
    // NOTE: this becomes `SELECT * FROM categories` (with a self-join or
    // second query for parent names) once PHP + MySQL are connected.
    let categories = [
        { id: 1, name: 'Men', parentId: null, productCount: 12 },
        { id: 2, name: 'Women', parentId: null, productCount: 18 },
        { id: 3, name: 'Shoes', parentId: null, productCount: 9 },
        { id: 4, name: 'Accessories', parentId: null, productCount: 14 },
        { id: 5, name: 'Jackets', parentId: 1, productCount: 5 },
        { id: 6, name: 'Dresses', parentId: 2, productCount: 7 }
    ];
    let nextId = 7;
    let categoryToDelete = null;

    const tbody = document.getElementById('categoriesTableBody');
    const parentSelect = document.getElementById('parentCategory');
    const form = document.getElementById('categoryForm');
    const nameInput = document.getElementById('categoryName');
    const nameError = document.getElementById('categoryNameError');
    const editingIdInput = document.getElementById('editingId');
    const formTitle = document.getElementById('formTitle');
    const submitBtn = document.getElementById('submitCategoryBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');

    function getCategoryName(id) {
        const cat = categories.find(c => c.id === id);
        return cat ? cat.name : '—';
    }

    function renderParentOptions() {
        const currentEditId = editingIdInput.value ? parseInt(editingIdInput.value) : null;
        parentSelect.innerHTML = '<option value="">None (top-level)</option>';
        categories
            .filter(c => c.parentId === null && c.id !== currentEditId) // top-level only, exclude self
            .forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.name;
                parentSelect.appendChild(opt);
            });
    }

    function renderCategories() {
        tbody.innerHTML = '';
        categories.forEach(c => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.innerHTML = `
                <td class="py-3 px-4 font-medium text-gray-900">${c.parentId ? '↳ ' : ''}${c.name}</td>
                <td class="py-3 px-4 text-gray-500">${c.parentId ? getCategoryName(c.parentId) : '—'}</td>
                <td class="py-3 px-4 text-gray-600">${c.productCount}</td>
                <td class="py-3 px-4">
                    <div class="flex items-center justify-end gap-2">
                        <button class="edit-btn p-2 text-gray-500 hover:text-black hover:bg-gray-100 rounded-lg" data-id="${c.id}">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        <button class="delete-btn p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg" data-id="${c.id}" data-name="${c.name}">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
        lucide.createIcons();
        attachRowEvents();
    }

    function attachRowEvents() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const cat = categories.find(c => c.id === parseInt(btn.dataset.id));
                editingIdInput.value = cat.id;
                nameInput.value = cat.name;
                renderParentOptions();
                parentSelect.value = cat.parentId || '';
                formTitle.textContent = 'Edit Category';
                submitBtn.textContent = 'Save Changes';
                cancelEditBtn.classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                categoryToDelete = parseInt(btn.dataset.id);
                document.getElementById('deleteModalText').textContent =
                    `Delete "${btn.dataset.name}"? Products in this category will need to be reassigned.`;
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteModal').classList.add('flex');
            });
        });
    }

    function resetForm() {
        form.reset();
        editingIdInput.value = '';
        formTitle.textContent = 'Add New Category';
        submitBtn.textContent = 'Add Category';
        cancelEditBtn.classList.add('hidden');
        nameError.classList.add('hidden');
        nameInput.classList.remove('border-red-500');
        renderParentOptions();
    }

    cancelEditBtn.addEventListener('click', resetForm);

    // ---- Add / Edit submit ----
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = nameInput.value.trim();

        if (!name) {
            nameError.classList.remove('hidden');
            nameInput.classList.add('border-red-500');
            return;
        }
        nameError.classList.add('hidden');
        nameInput.classList.remove('border-red-500');

        const parentId = parentSelect.value ? parseInt(parentSelect.value) : null;
        const editId = editingIdInput.value ? parseInt(editingIdInput.value) : null;

        // NOTE: real save runs an INSERT or UPDATE against the
        // `categories` table via PHP in the backend phase.
        if (editId) {
            const cat = categories.find(c => c.id === editId);
            cat.name = name;
            cat.parentId = parentId;
            showToast('Category updated.');
        } else {
            categories.push({ id: nextId++, name, parentId, productCount: 0 });
            showToast('Category added.');
        }

        resetForm();
        renderCategories();
    });

    // ---- Delete modal ----
    const deleteModal = document.getElementById('deleteModal');
    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
        categoryToDelete = null;
    }
    document.getElementById('deleteCancelBtn').addEventListener('click', closeDeleteModal);
    document.getElementById('deleteModalOverlay').addEventListener('click', closeDeleteModal);
    document.getElementById('deleteConfirmBtn').addEventListener('click', () => {
        if (categoryToDelete !== null) {
            categories = categories.filter(c => c.id !== categoryToDelete);
            renderCategories();
            renderParentOptions();
            showToast('Category deleted.');
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

    renderParentOptions();
    renderCategories();
</script>

</body>
</html>