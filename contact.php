<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | ShopName</title>
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
                <a href="contact.php" class="text-sm font-medium text-gray-900">Contact</a>
            </nav>

            <div class="flex items-center gap-4">
                <button id="searchToggle" class="text-gray-700 hover:text-black">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
                <a href="cart.php" class="relative text-gray-700 hover:text-black">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span class="absolute -top-2 -right-2 bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">2</span>
                </a>
                <a href="account.php" class="hidden md:block text-gray-700 hover:text-black">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </a>
                <button id="menuToggle" class="md:hidden text-gray-700 hover:text-black">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <div id="searchBar" class="hidden pb-4">
            <div class="relative flex gap-2">
                <div class="relative flex-1">
                    <input type="text" id="headerSearchInput" placeholder="Search products..."
                        class="w-full border border-gray-300 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
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
            <a href="orders.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="package" class="w-5 h-5"></i><span class="text-sm font-medium">My Orders</span>
            </a>
            <a href="about.php" class="flex items-center gap-3 py-3 px-2 rounded-lg text-gray-700 hover:bg-gray-100">
                <i data-lucide="info" class="w-5 h-5"></i><span class="text-sm font-medium">About</span>
            </a>
            <a href="contact.php" class="flex items-center gap-3 py-3 px-2 rounded-lg bg-gray-100 text-gray-900">
                <i data-lucide="mail" class="w-5 h-5"></i><span class="text-sm font-medium">Contact</span>
            </a>
        </nav>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="text-center mb-10">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Get in Touch</h1>
        <p class="text-sm text-gray-500 max-w-md mx-auto">
            Questions about an order, a product, or anything else? We're here to help.
        </p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- ===================== CONTACT INFO ===================== -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-start gap-4">
                <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="mail" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Email</p>
                    <p class="text-sm text-gray-500">support@shopname.com</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-start gap-4">
                <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="phone" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Phone</p>
                    <p class="text-sm text-gray-500">+234 800 000 0000</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-start gap-4">
                <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="map-pin" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Address</p>
                    <p class="text-sm text-gray-500">12 Palm Avenue, Lekki, Lagos, Nigeria</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5 flex items-start gap-4">
                <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clock" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Support Hours</p>
                    <p class="text-sm text-gray-500">Mon–Sat, 9AM–6PM WAT</p>
                </div>
            </div>
        </div>

        <!-- ===================== CONTACT FORM ===================== -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8">

                <form id="contactForm" class="space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Full Name</label>
                            <input type="text" name="name" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            <p class="error-msg hidden text-xs text-red-500 mt-1">Name is required.</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Email Address</label>
                            <input type="email" name="email" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            <p class="error-msg hidden text-xs text-red-500 mt-1">Enter a valid email address.</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Subject</label>
                        <select name="subject" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                            <option>Order Inquiry</option>
                            <option>Product Question</option>
                            <option>Returns & Refunds</option>
                            <option>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Message</label>
                        <textarea name="message" rows="5" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black resize-none"></textarea>
                        <p class="error-msg hidden text-xs text-red-500 mt-1">Please enter a message.</p>
                        <p id="charCount" class="text-xs text-gray-400 mt-1">0 / 500</p>
                    </div>

                    <button type="submit" id="submitContactBtn" class="w-full sm:w-auto bg-black text-white font-semibold px-8 py-3 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i> Send Message
                    </button>
                </form>

                <!-- Success state -->
                <div id="contactSuccess" class="hidden text-center py-10">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="check" class="w-7 h-7 text-green-600"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Message Sent!</h2>
                    <p class="text-sm text-gray-500">We'll get back to you within 24 hours.</p>
                </div>
            </div>
        </div>
    </div>

</main>

<!-- ===================== FOOTER ===================== -->
<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-8">            <div>
                <h3 class="text-white font-bold text-lg mb-3">ShopName</h3>
                <p class="text-sm text-gray-400">Quality products, delivered to your door.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Shop</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="shop.php" class="hover:text-white">All Products</a></li>
                    <li><a href="shop.php?category=new" class="hover:text-white">New Arrivals</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-3">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="contact.php" class="hover:text-white">Contact Us</a></li>
                    <li><a href="orders.php" class="hover:text-white">Track Order</a></li>
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
            &copy; 2026 ShopName. All rights reserved.
        </div>
    </div>
</footer>

<script>
    lucide.createIcons();
    document.getElementById('menuToggle').addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
    document.getElementById('searchToggle').addEventListener('click', () => {
        document.getElementById('searchBar').classList.toggle('hidden');
    });
    document.getElementById('headerSearchInput').addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const term = e.target.value.trim();
            if (term) window.location.href = `search.php?q=${encodeURIComponent(term)}`;
        }
    });

    // ---- Character counter ----
    const messageField = document.querySelector('textarea[name="message"]');
    const charCount = document.getElementById('charCount');
    messageField.addEventListener('input', () => {
        const len = messageField.value.length;
        charCount.textContent = `${len} / 500`;
        charCount.classList.toggle('text-red-500', len > 500);
    });

    // ---- Form validation + submit ----
    const form = document.getElementById('contactForm');
    const successBlock = document.getElementById('contactSuccess');
    const submitBtn = document.getElementById('submitContactBtn');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let valid = true;

        form.querySelectorAll('[required]').forEach(field => {
            const errorMsg = field.parentElement.querySelector('.error-msg');
            let fieldValid = field.value.trim() !== '';

            if (field.type === 'email' && fieldValid) {
                fieldValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
            }

            if (!fieldValid) {
                field.classList.add('border-red-500');
                if (errorMsg) errorMsg.classList.remove('hidden');
                valid = false;
            } else {
                field.classList.remove('border-red-500');
                if (errorMsg) errorMsg.classList.add('hidden');
            }
        });

        if (messageField.value.length > 500) valid = false;
        if (!valid) return;

        // NOTE: real submission sends this via PHP mail() or a mail API
        // (e.g. PHPMailer) in the backend phase — possibly also saving
        // it to a `contact_messages` table. This confirms the full
        // client-side flow (validation → submit) works correctly.
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Sending...';
        lucide.createIcons();

        setTimeout(() => {
            form.classList.add('hidden');
            successBlock.classList.remove('hidden');
        }, 1200);
    });
</script>

</body>
</html>