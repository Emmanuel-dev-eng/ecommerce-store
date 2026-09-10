<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | ShopName Admin</title>
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
                <a href="products.php" class="text-gray-500 hover:text-black flex items-center gap-1 text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                </a>
                <h1 class="text-lg font-bold text-gray-900">Add Product</h1>
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

            <form id="productForm" class="grid lg:grid-cols-3 gap-6">

                <!-- LEFT: main form -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Basic Info -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-4">Basic Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Product Name</label>
                                <input type="text" id="productName" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Description</label>
                                <textarea rows="4" id="productDesc"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black resize-none"></textarea>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Category</label>
                                    <select id="productCategory" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                        <option>Men</option>
                                        <option>Women</option>
                                        <option>Shoes</option>
                                        <option>Accessories</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Base Price ($)</label>
                                    <input type="number" step="0.01" id="productPrice" required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <h2 class="text-sm font-bold text-gray-900 mb-4">Product Images</h2>
                        <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-gray-400 transition">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                            <p class="text-sm text-gray-600 font-medium">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG up to 5MB each</p>
                            <input type="file" id="imageInput" accept="image/*" multiple class="hidden">
                        </div>
                        <div id="imagePreviewGrid" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mt-4"></div>
                    </div>

                    <!-- Variant Builder -->
                    <div class="bg-white rounded-2xl border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-bold text-gray-900">Variants</h2>
                            <button type="button" id="addVariantBtn" class="text-xs font-semibold text-gray-900 border border-gray-300 rounded-lg px-3 py-1.5 hover:bg-gray-50 flex items-center gap-1">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Variant
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Each variant is a specific combination (e.g. Size M / Red) with its own stock and price.</p>

                        <div id="variantsContainer" class="space-y-3">
                            <!-- Variant rows injected/added here -->
                        </div>
                    </div>
                </div>

                <!-- RIGHT: publish + status -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-sm font-bold text-gray-900 mb-4">Publish</h2>

                        <label class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-700">Active (visible in shop)</span>
                            <input type="checkbox" id="productActive" checked class="w-4 h-4 rounded border-gray-300">
                        </label>

                        <button type="submit" id="saveProductBtn" class="w-full bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save Product
                        </button>

                        <div id="saveSuccessMsg" class="hidden mt-4 bg-green-50 border border-green-200 text-green-700 text-xs rounded-lg px-3 py-2.5 flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 flex-shrink-0"></i>
                            <span>Product saved successfully!</span>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
    lucide.createIcons();

    const drawer = document.getElementById('adminMobileDrawer');
    document.getElementById('adminMenuToggle').addEventListener('click', () => drawer.classList.remove('hidden'));
    document.getElementById('adminDrawerClose').addEventListener('click', () => drawer.classList.add('hidden'));
    document.getElementById('adminDrawerOverlay').addEventListener('click', () => drawer.classList.add('hidden'));

    // ---- Image upload preview ----
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('imageInput');
    const previewGrid = document.getElementById('imagePreviewGrid');
    let uploadedImages = [];

    dropZone.addEventListener('click', () => imageInput.click());
    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.classList.add('border-black'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-black'));
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-black');
        handleFiles(e.dataTransfer.files);
    });
    imageInput.addEventListener('change', () => handleFiles(imageInput.files));

    function handleFiles(fileList) {
        Array.from(fileList).forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const id = Date.now() + Math.random();
                uploadedImages.push({ id, src: e.target.result });
                renderImagePreviews();
            };
            reader.readAsDataURL(file);
        });
    }

    function renderImagePreviews() {
        previewGrid.innerHTML = '';
        uploadedImages.forEach((img, index) => {
            const div = document.createElement('div');
            div.className = 'relative rounded-lg overflow-hidden aspect-square border border-gray-200';
            div.innerHTML = `
                <img src="${img.src}" class="w-full h-full object-cover">
                ${index === 0 ? '<span class="absolute bottom-1 left-1 bg-black text-white text-[9px] font-semibold px-1.5 py-0.5 rounded">Main</span>' : ''}
                <button type="button" class="remove-img-btn absolute top-1 right-1 bg-white/90 rounded-full p-1 hover:bg-white" data-id="${img.id}">
                    <i data-lucide="x" class="w-3 h-3 text-gray-700"></i>
                </button>
            `;
            previewGrid.appendChild(div);
        });
        lucide.createIcons();
        document.querySelectorAll('.remove-img-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                uploadedImages = uploadedImages.filter(i => i.id != btn.dataset.id);
                renderImagePreviews();
            });
        });
    }

    // ---- Variant builder ----
    const variantsContainer = document.getElementById('variantsContainer');
    let variantCount = 0;

    function addVariantRow(size = '', color = '', price = '', stock = '') {
        variantCount++;
        const row = document.createElement('div');
        row.className = 'variant-row grid grid-cols-2 sm:grid-cols-5 gap-2 items-center border border-gray-200 rounded-lg p-3';
        row.innerHTML = `
            <select class="variant-size border border-gray-300 rounded-lg px-2 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-black">
                <option value="">Size</option>
                <option ${size==='S'?'selected':''}>S</option>
                <option ${size==='M'?'selected':''}>M</option>
                <option ${size==='L'?'selected':''}>L</option>
                <option ${size==='XL'?'selected':''}>XL</option>
            </select>
            <select class="variant-color border border-gray-300 rounded-lg px-2 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-black">
                <option value="">Color</option>
                <option ${color==='White'?'selected':''}>White</option>
                <option ${color==='Black'?'selected':''}>Black</option>
                <option ${color==='Red'?'selected':''}>Red</option>
                <option ${color==='Blue'?'selected':''}>Blue</option>
            </select>
            <input type="number" step="0.01" placeholder="Price" value="${price}" class="variant-price border border-gray-300 rounded-lg px-2 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-black">
            <input type="number" placeholder="Stock" value="${stock}" class="variant-stock border border-gray-300 rounded-lg px-2 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-black">
            <button type="button" class="remove-variant-btn text-red-500 hover:bg-red-50 rounded-lg p-2 flex items-center justify-center">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        `;
        variantsContainer.appendChild(row);
        lucide.createIcons();

        row.querySelector('.remove-variant-btn').addEventListener('click', () => {
            row.remove();
        });
    }

    document.getElementById('addVariantBtn').addEventListener('click', () => addVariantRow());
    // Start with one empty variant row so the form isn't intimidatingly empty
    addVariantRow();

    // ---- Save product ----
    document.getElementById('productForm').addEventListener('submit', (e) => {
        e.preventDefault();

        const name = document.getElementById('productName').value.trim();
        const price = document.getElementById('productPrice').value;

        if (!name || !price) {
            alert('Please fill in the product name and base price.');
            return;
        }

        // NOTE: this is where the real submission happens in the PHP
        // phase — an INSERT into `products`, one INSERT per row into
        // `product_variants`, and file uploads saved to disk with paths
        // stored in `product_images`. For now, this confirms the full
        // form structure and data collection works correctly client-side.
        const variantRows = document.querySelectorAll('.variant-row');
        const variants = Array.from(variantRows).map(row => ({
            size: row.querySelector('.variant-size').value,
            color: row.querySelector('.variant-color').value,
            price: row.querySelector('.variant-price').value,
            stock: row.querySelector('.variant-stock').value
        }));

        console.log('Product to save:', {
            name,
            description: document.getElementById('productDesc').value,
            category: document.getElementById('productCategory').value,
            price,
            active: document.getElementById('productActive').checked,
            images: uploadedImages.length,
            variants
        });

        const successMsg = document.getElementById('saveSuccessMsg');
        successMsg.classList.remove('hidden');
        setTimeout(() => successMsg.classList.add('hidden'), 3000);
    });
</script>

</body>
</html>