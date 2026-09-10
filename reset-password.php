<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | ShopName</title>
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

    <!-- ===================== STEP 1: NEW PASSWORD FORM ===================== -->
    <div id="resetStep">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="lock-keyhole" class="w-6 h-6 text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Set a New Password</h1>
            <p class="text-sm text-gray-500">Choose a strong password you haven't used before.</p>
        </div>
 
        <!-- Invalid/expired link state -->
        <div id="invalidLinkBox" class="hidden bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl p-4 mb-6 flex items-start gap-2">
            <i data-lucide="alert-triangle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
            <span>This reset link is invalid or has expired. Please request a new one.</span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8">
            <form id="resetForm" class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="newPassword" required
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
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="confirmPassword" id="confirmPassword" required
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <i data-lucide="lock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p class="error-msg hidden text-xs text-red-500 mt-1">Passwords do not match.</p>
                </div>

                <button type="submit" id="resetBtn" class="w-full bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2">
                    Reset Password
                </button>
            </form>
        </div>
    </div>

    <!-- ===================== STEP 2: SUCCESS ===================== -->
    <div id="successStep" class="hidden">
        <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center">
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check" class="w-7 h-7 text-green-600"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mb-2">Password Reset!</h1>
            <p class="text-sm text-gray-500 mb-6">Your password has been changed successfully. You can now log in.</p>
            <a href="login.php" class="inline-block bg-black text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-gray-800">
                Go to Login
            </a>
        </div>
    </div>

</main>

<script>
    lucide.createIcons();

    // ---- Simulated token check ----
    // NOTE: real logic validates the ?token= from the URL against a
    // `password_resets` table (checking it exists and hasn't expired,
    // typically within 1 hour) via PHP in the backend phase, BEFORE
    // showing this form at all. For now we simulate an invalid-link
    // state so you can see that UI too.
    const params = new URLSearchParams(window.location.search);
    const token = params.get('token');
    const resetForm = document.getElementById('resetForm');
    const resetBtn = document.getElementById('resetBtn');

    if (token === 'expired') {
        document.getElementById('invalidLinkBox').classList.remove('hidden');
        resetForm.querySelectorAll('input, button[type="submit"]').forEach(el => el.disabled = true);
        resetForm.classList.add('opacity-50');
    }

    // ---- Show/hide password ----
    const newPassword = document.getElementById('newPassword');
    const toggleBtn = document.getElementById('togglePassword');
    toggleBtn.addEventListener('click', () => {
        const isHidden = newPassword.type === 'password';
        newPassword.type = isHidden ? 'text' : 'password';
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

    newPassword.addEventListener('input', () => {
        const val = newPassword.value;
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

    // ---- Form validation + submit ----
    resetForm.addEventListener('submit', (e) => {
        e.preventDefault();
        let valid = true;
        const confirmInput = document.getElementById('confirmPassword');

        [newPassword, confirmInput].forEach(input => {
            const errorMsg = input.closest('div').parentElement.querySelector('.error-msg')
                || input.parentElement.parentElement.querySelector('.error-msg');
            let fieldValid = input.value.trim() !== '';

            if (input === newPassword && fieldValid) fieldValid = input.value.length >= 8;
            if (input === confirmInput && fieldValid) fieldValid = input.value === newPassword.value;

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

        // NOTE: real save hashes the new password with password_hash()
        // and runs an UPDATE on the `users` table, then invalidates the
        // reset token, via PHP in the backend phase.
        resetBtn.disabled = true;
        resetBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Resetting...';
        lucide.createIcons();

        setTimeout(() => {
            document.getElementById('resetStep').classList.add('hidden');
            document.getElementById('successStep').classList.remove('hidden');
        }, 1200);
    });
</script>

</body>
</html>