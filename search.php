<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER ===================== -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight flex-shrink-0">ShopName</a>

            <!-- Persistent search bar (this page is search-first) -->
            <div class="flex-1 max-w-xl relative">
                <input type="text" id="searchInput" placeholder="Search products..."
                    class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>

            <div class="flex items-center gap-4 flex-shrink-0">
                <a href="cart.php" class="relative text-gray-700 hover:text-black">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span class="absolute -top-2 -right-2 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">2</span>
                </a>
                <a href="account.php" class="hidden md:block text-gray-700 hover:text-black">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <p id="resultsSummary" class="text-sm text-gray-500 mb-6">Search for something above</p>

    <!-- ===================== RESULTS GRID ===================== -->
    <div id="resultsGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Injected by JS -->
    </div>

    <!-- ===================== NO RESULTS STATE ===================== -->
    <div id="noResultsMsg" class="hidden text-center py-16">
        <i data-lucide="search-x" class="w-12 h-12 text-gray-300 mx-auto mb-4"></i>
        <p class="text-gray-700 font-medium mb-1">No products found</p>
        <p class="text-sm text-gray-500 mb-4">Try a different keyword or browse all products.</p>
        <a href="shop.php" class="inline-block bg-black text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-gray-800">
            Browse Shop
        </a>
    </div>

</main>

<script>
    lucide.createIcons();

    // ---- Product catalog for search ----
    // NOTE: in the PHP phase this becomes a real SQL query:
    // SELECT * FROM products WHERE name LIKE '%term%' OR description LIKE '%term%'
    // For now it searches this in-memory array, which proves the logic works.
    const catalog = [
        { id: 1, name: 'Classic White Sneakers', category: 'Shoes', price: 59.99, image: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&q=80' },
        { id: 2, name: 'Oversized Denim Jacket', category: 'Men', price: 89.99, image: 'https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?w=400&q=80' },
        { id: 3, name: 'Leather Crossbody Bag', category: 'Accessories', price: 74.99, image: 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=400&q=80' },
        { id: 4, name: 'Minimalist Wrist Watch', category: 'Accessories', price: 129.99, image: 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&q=80' },
        { id: 5, name: 'Wool Blend Overcoat', category: 'Women', price: 149.99, image: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=400&q=80' },
        { id: 6, name: 'Running Sneakers', category: 'Shoes', price: 99.99, image: 'https://images.unsplash.com/photo-1560343090-f0409e92791a?w=400&q=80' },
        { id: 7, name: 'Slim Fit Chinos', category: 'Men', price: 54.99, image: 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=400&q=80' },
        { id: 8, name: 'Silk Scarf', category: 'Accessories', price: 39.99, image: 'https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=400&q=80' }
    ];

    const grid = document.getElementById('resultsGrid');
    const summary = document.getElementById('resultsSummary');
    const noResultsMsg = document.getElementById('noResultsMsg');
    const searchInput = document.getElementById('searchInput');

    function renderResults(term) {
        grid.innerHTML = '';

        if (!term) {
            summary.textContent = 'Search for something above';
            noResultsMsg.classList.add('hidden');
            return;
        }

        const matches = catalog.filter(p =>
            p.name.toLowerCase().includes(term.toLowerCase()) ||
            p.category.toLowerCase().includes(term.toLowerCase())
        );

        summary.innerHTML = `${matches.length} result${matches.length !== 1 ? 's' : ''} for "<span class="font-medium text-gray-900">${term}</span>"`;

        if (matches.length === 0) {
            noResultsMsg.classList.remove('hidden');
            return;
        }
        noResultsMsg.classList.add('hidden');

        matches.forEach(p => {
            const card = document.createElement('a');
            card.href = `product.php?id=${p.id}`;
            card.className = 'group';
            card.innerHTML = `
                <div class="relative rounded-xl overflow-hidden bg-gray-100 aspect-square mb-3">
                    <img src="${p.image}" alt="${p.name}" class="w-full h-full object-cover group-hover:scale-105 transition">
                </div>
                <h3 class="text-sm font-medium text-gray-900 truncate">${p.name}</h3>
                <p class="text-xs text-gray-400">${p.category}</p>
                <p class="text-sm font-bold text-gray-900 mt-1">$${p.price.toFixed(2)}</p>
            `;
            grid.appendChild(card);
        });
    }

    // ---- Read ?q= from URL on load (e.g. search.php?q=sneakers) ----
    const params = new URLSearchParams(window.location.search);
    const initialQuery = params.get('q') || '';
    searchInput.value = initialQuery;
    renderResults(initialQuery);

    // ---- Live search as you type (debounced) ----
    let debounceTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            renderResults(searchInput.value.trim());
        }, 250);
    });
</script>

</body>
</html>