<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | ShopName</title>
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

    <!-- ===================== STEP 1: REQUEST FORM ===================== -->
    <div id="requestStep">
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="key-round" class="w-6 h-6 text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Forgot Password?</h1>
            <p class="text-sm text-gray-500">Enter your email and we'll send you a reset link.</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8">
            <form id="forgotForm" class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1 block">Email Address</label>
                    <div class="relative">
                        <input type="email" name="email" required
                            class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <i data-lucide="mail" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p id="emailError" class="hidden text-xs text-red-500 mt-1">Enter a valid email address.</p>
                    <p id="notFoundError" class="hidden text-xs text-red-500 mt-1">No account found with that email.</p>
                </div>

                <button type="submit" id="sendResetBtn" class="w-full bg-black text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2">
                    Send Reset Link
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Remembered your password?
            <a href="login.php" class="font-semibold text-gray-900 hover:underline">Log in</a>
        </p>
    </div>

    <!-- ===================== STEP 2: CHECK YOUR EMAIL ===================== -->
    <div id="confirmationStep" class="hidden">
        <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center">
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="mail-check" class="w-6 h-6 text-green-600"></i>
            </div>
            <h1 class="text-xl font-bold text-gray-900 mb-2">Check Your Email</h1>
            <p class="text-sm text-gray-500 mb-1">We've sent a password reset link to</p>
            <p id="sentToEmail" class="text-sm font-semibold text-gray-900 mb-6"></p>

            <p class="text-xs text-gray-400 mb-4">Didn't get the email? Check your spam folder, or</p>
            <button id="resendBtn" class="text-sm font-semibold text-gray-900 hover:underline">Resend Link</button>
            <p id="resendMsg" class="hidden text-xs text-green-600 mt-2">Link resent!</p>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            <a href="login.php" class="font-semibold text-gray-900 hover:underline flex items-center justify-center gap-1">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Login
            </a>
        </p>
    </div>

</main>

<script>
    lucide.createIcons();

    const form = document.getElementById('forgotForm');
    const emailInput = form.email;
    const emailError = document.getElementById('emailError');
    const notFoundError = document.getElementById('notFoundError');
    const sendBtn = document.getElementById('sendResetBtn');
    const requestStep = document.getElementById('requestStep');
    const confirmationStep = document.getElementById('confirmationStep');

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        emailError.classList.add('hidden');
        notFoundError.classList.add('hidden');
        emailInput.classList.remove('border-red-500');

        const email = emailInput.value.trim();
        const isValidFormat = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

        if (!isValidFormat) {
            emailError.classList.remove('hidden');
            emailInput.classList.add('border-red-500');
            return;
        }

        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Sending...';
        lucide.createIcons();

        // NOTE: real logic checks the `users` table for this email and,
        // if found, generates a secure token + sends an email with a
        // reset link (PHP mail/PHPMailer) in the backend phase. This
        // demo simulates a "not found" case so you can see that state too.
        setTimeout(() => {
            if (email === 'notfound@example.com') {
                notFoundError.classList.remove('hidden');
                emailInput.classList.add('border-red-500');
                sendBtn.disabled = false;
                sendBtn.textContent = 'Send Reset Link';
                return;
            }

            document.getElementById('sentToEmail').textContent = email;
            requestStep.classList.add('hidden');
            confirmationStep.classList.remove('hidden');
        }, 1200);
    });

    // ---- Resend link ----
    document.getElementById('resendBtn').addEventListener('click', () => {
        const msg = document.getElementById('resendMsg');
        msg.classList.remove('hidden');
        setTimeout(() => msg.classList.add('hidden'), 3000);
    });
</script>

</body>
</html>