<?php
session_start();
require_once 'config/database.php';

// Jika sudah login, langsung lempar ke dashboard
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($db, trim($_POST['username']));
    $password_input = $_POST['password'];

    if (empty($username) || empty($password_input)) {
        $error = 'Username dan password wajib diisi!';
    } else {
        // Enkripsi password input dengan MD5 agar sama dengan format di database
        $password_hash = md5($password_input);

        // Cari user di database berdasarkan username dan password yang sudah di-hash
        $query = mysqli_query($db, "SELECT * FROM user WHERE username = '$username' AND password = '$password_hash'");

        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);

            // Set session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $data['username'];

            header("Location: index.php");
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Login - SMAS MKGR Sepatan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-4 relative overflow-hidden">

    <!-- Elemen Dekoratif Background (Grid & Glow) -->
    <div class="absolute inset-0 opacity-[0.03] bg-[linear-gradient(to_right,#ffffff_1px,transparent_1px),linear-gradient(to_bottom,#ffffff_1px,transparent_1px)] bg-[size:32px_32px]"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Container Utama (Card Login Tengah) -->
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 md:p-10 z-10 border border-slate-100/80">

        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 mb-3 border border-indigo-100/60 tracking-wide mx-auto">
                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                Sistem Manajemen Penjadwalan v1.0
            </div>

            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Portal Log In</h2>
            <p class="mt-2 text-xs text-slate-400 max-w-[280px] mx-auto leading-relaxed">
                Silakan otentikasi akun Anda untuk mengelola instrumen jadwal pengajaran terintegrasi.
            </p>

            <!-- LOGO DI BAWAH PORTAL LOGIN (Dikasih base putih biar gak bolong) -->
            <div class="mt-5 flex justify-center">
                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-100 shadow-sm inline-block">
                    <img src="kelasss.png" alt="Logo SMAS MKGR" class="h-16 w-16 object-contain">
                </div>
            </div>
        </div>

        <!-- Notifikasi Error State -->
        <?php if (!empty($error)): ?>
            <div class="mb-5 p-4 bg-red-50 border border-red-100 text-red-900 rounded-2xl flex items-start gap-3 text-xs font-medium shadow-sm">
                <svg class="w-4 h-4 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="leading-relaxed"><?= $error; ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Eksekusi -->
        <form action="login.php" method="POST" class="space-y-5">

            <!-- Input Username -->
            <div>
                <label for="username" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Username / NIP</label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input id="username" name="username" type="text" required autocomplete="off" placeholder="Masukkan username"
                        class="block w-full rounded-xl border border-slate-200 pl-11 pr-4 py-3 text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/5 transition-all outline-none font-medium">
                </div>
            </div>

            <!-- Input Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input id="password" name="password" type="password" required placeholder="••••••••"
                        class="block w-full rounded-xl border border-slate-200 pl-11 pr-4 py-3 text-sm text-slate-800 placeholder-slate-400 bg-slate-50/50 focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/5 transition-all outline-none font-medium">
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-2">
                <button type="submit" class="group w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-xl focus:ring-4 focus:ring-slate-900/20 active:scale-[0.98] transition-all shadow-md tracking-wide flex items-center justify-center gap-2">
                    Masuk ke Dashboard
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="lupa_password.php" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 transition-all">
                Lupa Kata Sandi?
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-8 border-t border-slate-100 pt-5">
            <p class="text-[11px] text-slate-400 text-center font-medium">&copy; 2026 SMAS MKGR Sepatan. All rights reserved.</p>
        </div>
    </div>

</body>

</html>