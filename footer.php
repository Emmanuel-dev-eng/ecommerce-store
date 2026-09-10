<?php
// ============================================
// footer.php
// Shared footer included on every page
// ============================================
?>
</main>

<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-8">
            <div>
                <h3 class="text-white font-bold text-lg mb-3">ShopName</h3>
                <p class="text-sm text-gray-400">Quality products, delivered to your door.</p>
            </div>

            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Shop</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/shop.php" class="hover:text-white">All Products</a></li>
                    <li><a href="/shop.php?category=new" class="hover:text-white">New Arrivals</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/contact.php" class="hover:text-white">Contact Us</a></li>
                    <li><a href="/orders.php" class="hover:text-white">Track Order</a></li>
                </ul>
            </div>
            <div>
    <h4 class="text-white font-semibold text-sm mb-3">Legal</h4>
    <ul class="space-y-2 text-sm">
        <li><a href="terms.php" class="hover:text-white">Terms of Service</a></li>
        <li><a href="privacy.php" class="hover:text-white">Privacy Policy</a></li>
    </ul>
</div>

            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Follow Us</h4>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-white"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                    <a href="#" class="hover:text-white"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                    <a href="#" class="hover:text-white"><i data-lucide="twitter" class="w-5 h-5"></i></a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-xs text-gray-500">
            &copy; <?= date('Y') ?> ShopName. All rights reserved.
        </div>
    </div>
</footer>

<script>
    // Render Lucide icons
    lucide.createIcons();

    // Mobile menu toggle
    document.getElementById('menuToggle').addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // Search bar toggle
    document.getElementById('searchToggle').addEventListener('click', () => {
        document.getElementById('searchBar').classList.toggle('hidden');
    });
</script>
</body>
</html>