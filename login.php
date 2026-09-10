<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER (simplified) ===================== -->
<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-center h-16">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight">ShopName</a>
        </div>
    </div>
</header>

<main class="max-w-md mx-auto px-4 sm:px-6 py-16">

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h1>
        <p class="text-sm text-gray-500">Log in to track orders and check out faster.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8">

        <form id="loginForm" class="space-y-4">

            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1 block">Email Address</label>
                <div class="relative">
                    <input type="email" name="email" required
                        class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="mail" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
                <p class="error-msg hidden text-xs text-red-500 mt-1">Enter a valid email address.</p>
            </div>

            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1 block">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="passwordInput" required
                        class="w-full border border-gray-300 rounded-lg pl-10 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="lock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="error-msg hidden text-xs text-red-500 mt-1">Password is required.</p>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-gray-600">
                    <input type="checkbox" class="rounded border-gray-300"> Remember me
                </label>
                <a href="forgot-password.php" class="font-medium text-gray-900 hover:underline">Forgot password?</a>
            </div>

            <!-- General error banner (e.g. wrong credentials) -->
            <div id="formError" class="hidden bg-red-50 border border-red-200 text-red-600 text-xs rounded-lg px-3 py-2.5 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                <span>Incorrect email or password. Please try again.</span>
            </div>

            <button type="submit" id="loginBtn" class="w-full bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2">
                Log In
            </button>
        </form>

        <div class="flex items-center gap-3 my-6">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs text-gray-400">or</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        <button class="w-full border border-gray-300 text-gray-700 font-medium py-3 rounded-lg hover:bg-gray-50 flex items-center justify-center gap-2 text-sm">
            <i data-lucide="chrome" class="w-4 h-4"></i>
            Continue with Google
        </button>
    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        Don't have an account?
        <a href="register.php" class="font-semibold text-gray-900 hover:underline">Sign up</a>
    </p>

</main>

<script>
    lucide.createIcons();

    // ---- Show/hide password ----
    const passwordInput = document.getElementById('passwordInput');
    const toggleBtn = document.getElementById('togglePassword');
    toggleBtn.addEventListener('click', () => {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';
        toggleBtn.innerHTML = isHidden
            ? '<i data-lucide="eye-off" class="w-4 h-4"></i>'
            : '<i data-lucide="eye" class="w-4 h-4"></i>';
        lucide.createIcons();
    });

    // ---- Form validation ----
    const form = document.getElementById('loginForm');
    const formError = document.getElementById('formError');
    const loginBtn = document.getElementById('loginBtn');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        formError.classList.add('hidden');
        let valid = true;

        form.querySelectorAll('input[required]').forEach(input => {
            const errorMsg = input.parentElement.parentElement.querySelector('.error-msg');
            let fieldValid = input.value.trim() !== '';

            if (input.type === 'email' && fieldValid) {
                fieldValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
            }

            if (!fieldValid) {
                input.classList.add('border-red-500');
                if (errorMsg) errorMsg.classList.remove('hidden');
                valid = false;
            } else {
                input.classList.remove('border-red-500');
                if (errorMsg) errorMsg.classList.add('hidden');
            }
        });

        if (!valid) return;

        // NOTE: this is where real authentication takes over in the PHP
        // phase — checking the email/password against the `users` table
        // (with password_verify) and starting a PHP session. For now we
        // simulate a loading state and just demonstrate the error state
        // exists and works, using a fake check.
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Logging in...';
        lucide.createIcons();

        setTimeout(() => {
            const email = form.email.value;
            // Demo rule: any email except test@fail.com "succeeds"
            if (email === 'test@fail.com') {
                formError.classList.remove('hidden');
                loginBtn.disabled = false;
                loginBtn.textContent = 'Log In';
            } else {
                window.location.href = 'account.php';
            }
        }, 1200);
    });
</script>

</body>
</html>