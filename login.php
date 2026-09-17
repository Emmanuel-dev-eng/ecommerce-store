 <?php
// ============================================
// login.php — top section
// ============================================

session_start(); // MUST be the very first thing — before any HTML output
require 'config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {

        // Look up this user by email
        $stmt = $pdo->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // fetch() grabs one matching row as an associative array,
        // e.g. $user['name'], $user['role'], etc. If no match, $user is false.

        // Check: does a user exist AND does the password match?
        if ($user && password_verify($password, $user['password_hash'])) {

            // Correct login! Store key info in the session so every
            // other page can check "is someone logged in, and who?"
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Role-based redirect — this is the Option 1 decision we made earlier
            if ($user['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: account.php");
            }
            exit;

        } else {
            // Generic message on purpose — we don't say WHICH part was
            // wrong (email vs password). This is a real security practice:
            // telling someone "email not found" vs "wrong password"
            // helps attackers guess which emails are registered.
            $errors[] = "Incorrect email or password. Please try again.";
        }
    }
}
?>
 
 
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

        <?php if (!empty($errors)): ?>
    <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mt-4">
        <ul class="list-disc list-inside space-y-1">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

        <p class="text-sm text-gray-500">Log in to track orders and check out faster.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-8">

        <form id="loginForm" class="space-y-4" method="POST">

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

    if (!valid) {
        e.preventDefault();
        return;
    }

    // Validation passed — let the real form submission to login.php
    // happen, where PHP checks the database and starts the session.
    loginBtn.disabled = true;
    loginBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Logging in...';
    lucide.createIcons();
});
</script>

</body>
</html>