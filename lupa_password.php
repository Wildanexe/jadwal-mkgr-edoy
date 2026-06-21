<?php
session_start();
require_once 'config/database.php';

$step = 1;
$error = "";
$user = null;

// PROSES VERIFIKASI
if (isset($_POST['verifikasi'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $jawaban = mysqli_real_escape_string($db, $_POST['jawaban']);
    
    $query = mysqli_query($db, "SELECT * FROM user WHERE username = '$username' AND jawaban_keamanan = '$jawaban'");
    
    if (mysqli_num_rows($query) > 0) {
        $step = 2;
        $user = mysqli_fetch_assoc($query);
    } else {
        $error = "Username atau Jawaban Keamanan salah!";
    }
}

// PROSES RESET PASSWORD
if (isset($_POST['reset_password'])) {
    $user_id = mysqli_real_escape_string($db, $_POST['user_id']);
    $pass_baru = md5($_POST['pass_baru']);
    
    mysqli_query($db, "UPDATE user SET password = '$pass_baru' WHERE id = '$user_id'");
    echo "<script>alert('Password berhasil direset! Silakan login kembali.'); window.location='login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - SMAS MKGR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 md:p-10 border border-slate-100">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pemulihan Akun</h2>
            <p class="text-xs text-slate-400 mt-2">Ikuti langkah-langkah di bawah untuk mereset kata sandi Anda.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="mb-5 p-3 bg-red-50 text-red-700 text-[11px] font-bold rounded-xl border border-red-100"><?= $error; ?></div>
        <?php endif; ?>

        <?php if ($step == 1): ?>
            <form method="POST" class="space-y-4">
                <input type="text" name="username" placeholder="Masukkan Username" required class="w-full p-3 text-sm rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition-all">
                
                <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                    <p class="text-[10px] uppercase font-bold text-indigo-800 mb-1">Pertanyaan Keamanan</p>
                    <p class="text-xs text-indigo-600 font-medium">Siapa nama hewan peliharaan pertama Anda?</p>
                </div>

                <input type="text" name="jawaban" placeholder="Masukkan Jawaban Anda" required class="w-full p-3 text-sm rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition-all">
                
                <button type="submit" name="verifikasi" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl transition-all shadow-md">Verifikasi Akun</button>
            </form>
        <?php else: ?>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <input type="password" name="pass_baru" placeholder="Masukkan Password Baru" required class="w-full p-3 text-sm rounded-xl border border-slate-200 focus:border-indigo-500 outline-none transition-all">
                <button type="submit" name="reset_password" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md">Simpan Password Baru</button>
            </form>
        <?php endif; ?>

        <div class="mt-6 text-center">
            <a href="login.php" class="text-[11px] font-bold text-slate-400 hover:text-slate-800 transition-all underline">Kembali ke Halaman Login</a>
        </div>
    </div>
</body>
</html>