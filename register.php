<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | ShopName</title>
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
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Create Your Account</h1>
        <p class="text-sm text-gray-500">Faster checkout, order tracking, and more.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8">

        <form id="registerForm" class="space-y-4" novalidate>

            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1 block">Full Name</label>
                <div class="relative">
                    <input type="text" name="fullName" required
                        class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
                <p class="error-msg hidden text-xs text-red-500 mt-1">Full name is required.</p>
            </div>

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
                <p class="error-msg hidden text-xs text-red-500 mt-1">Password must be at least 8 characters.</p>

                <!-- Live password strength meter -->
                <div class="mt-2">
                    <div class="flex gap-1 h-1.5">
                        <div id="bar1" class="flex-1 rounded-full bg-gray-200"></div>
                        <div id="bar2" class="flex-1 rounded-full bg-gray-200"></div>
                        <div id="bar3" class="flex-1 rounded-full bg-gray-200"></div>
                        <div id="bar4" class="flex-1 rounded-full bg-gray-200"></div>
                    </div>
                    <p id="strengthLabel" class="text-[11px] text-gray-400 mt-1">Password strength</p>
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1 block">Confirm Password</label>
                <div class="relative">
                    <input type="password" name="confirmPassword" id="confirmPasswordInput" required
                        class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <i data-lucide="lock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
                <p class="error-msg hidden text-xs text-red-500 mt-1">Passwords do not match.</p>
            </div>

            <div>
                <label class="flex items-start gap-2 text-xs text-gray-600">
                    <input type="checkbox" name="terms" id="termsCheckbox" required class="rounded border-gray-300 mt-0.5">
<span>I agree to the <a href="terms.php" class="font-medium text-gray-900 hover:underline">Terms of Service</a> and <a href="privacy.php" class="font-medium text-gray-900 hover:underline">Privacy Policy</a>.</span>                </label>
                <p id="termsError" class="hidden text-xs text-red-500 mt-1">You must agree to continue.</p>
            </div>

            <!-- General error banner (e.g. email already exists) -->
            <div id="formError" class="hidden bg-red-50 border border-red-200 text-red-600 text-xs rounded-lg px-3 py-2.5 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                <span>An account with this email already exists.</span>
            </div>

            <button type="submit" id="registerBtn" class="w-full bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2">
                Create Account
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        Already have an account?
        <a href="login.php" class="font-semibold text-gray-900 hover:underline">Log in</a>
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

    // ---- Live password strength meter ----
    const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
    const strengthLabel = document.getElementById('strengthLabel');
    const strengthColors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-500'];
    const strengthText = ['Weak', 'Fair', 'Good', 'Strong'];

    passwordInput.addEventListener('input', () => {
        const val = passwordInput.value;
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        bars.forEach((bar, i) => {
            bar.className = i < score
                ? `flex-1 rounded-full ${strengthColors[score - 1]}`
                : 'flex-1 rounded-full bg-gray-200';
        });

        strengthLabel.textContent = val.length === 0 ? 'Password strength' : strengthText[Math.max(score - 1, 0)];
    });

    // ---- Form validation ----
    const form = document.getElementById('registerForm');
    const formError = document.getElementById('formError');
    const registerBtn = document.getElementById('registerBtn');
    const confirmInput = document.getElementById('confirmPasswordInput');
    const termsCheckbox = document.getElementById('termsCheckbox');
    const termsError = document.getElementById('termsError');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        formError.classList.add('hidden');
        let valid = true;

        form.querySelectorAll('input[required]:not([type=checkbox])').forEach(input => {
            const errorMsg = input.closest('div').parentElement.querySelector('.error-msg')
                || input.parentElement.parentElement.querySelector('.error-msg');
            let fieldValid = input.value.trim() !== '';

            if (input.name === 'email' && fieldValid) {
                fieldValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
            }
            if (input.name === 'password' && fieldValid) {
                fieldValid = input.value.length >= 8;
            }
            if (input.name === 'confirmPassword' && fieldValid) {
                fieldValid = input.value === passwordInput.value;
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

        if (!termsCheckbox.checked) {
            termsError.classList.remove('hidden');
            valid = false;
        } else {
            termsError.classList.add('hidden');
        }

        if (!valid) return;

        // NOTE: real account creation (hashing the password with
        // password_hash and inserting into the `users` table) happens
        // in the PHP phase. This demo simulates a duplicate-email check.
        registerBtn.disabled = true;
        registerBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Creating account...';
        lucide.createIcons();

        setTimeout(() => {
            const email = form.email.value;
            if (email === 'taken@example.com') {
                formError.classList.remove('hidden');
                registerBtn.disabled = false;
                registerBtn.textContent = 'Create Account';
            } else {
                window.location.href = 'account.php';
            }
        }, 1200);
    });
</script>

</body>
</html>