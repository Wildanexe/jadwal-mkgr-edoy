<?php
session_start();
require_once 'config/database.php';

// Proteksi halaman
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$pesan_error = "";
$pesan_sukses = "";

// Ambil data user
$query_user = mysqli_query($db, "SELECT * FROM user LIMIT 1");
$data_user = mysqli_fetch_assoc($query_user);

// Jika tabel kosong, cegah error lebih lanjut
if (!$data_user) {
    die("Data user tidak ditemukan di database. Pastikan tabel 'user' memiliki isi.");
}

$user_id = $data_user['id'];

// 1. PROSES UPDATE USERNAME
if (isset($_POST['update_profil'])) {
    $username_baru = mysqli_real_escape_string($db, $_POST['username']);
    $update_profil = mysqli_query($db, "UPDATE user SET username = '$username_baru' WHERE id = '$user_id'");
    
    if ($update_profil) {
        $pesan_sukses = "✅ Username berhasil diperbarui!";
        $data_user['username'] = $username_baru; 
    } else {
        $pesan_error = "⚠️ Gagal memperbarui username.";
    }
}

// 2. PROSES UPDATE PASSWORD (MD5)
if (isset($_POST['update_password'])) {
    $pass_lama = mysqli_real_escape_string($db, $_POST['pass_lama']);
    $pass_baru = mysqli_real_escape_string($db, $_POST['pass_baru']);
    $konfirmasi = mysqli_real_escape_string($db, $_POST['konfirmasi']);

    if (md5($pass_lama) !== $data_user['password']) {
        $pesan_error = "⚠️ Password Lama yang Anda masukkan salah!";
    } elseif ($pass_baru !== $konfirmasi) {
        $pesan_error = "⚠️ Konfirmasi Password Baru tidak cocok!";
    } elseif (empty($pass_baru)) {
        $pesan_error = "⚠️ Password Baru tidak boleh kosong!";
    } else {
        $pass_baru_md5 = md5($pass_baru);
        $update_pass = mysqli_query($db, "UPDATE user SET password = '$pass_baru_md5' WHERE id = '$user_id'");
        
        if ($update_pass) {
            $pesan_sukses = "✅ Kata Sandi berhasil diperbarui!";
            $data_user['password'] = $pass_baru_md5;
        } else {
            $pesan_error = "⚠️ Gagal memperbarui kata sandi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - SMAS MKGR</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>
        <div class="flex-1 p-10">
            <div class="max-w-2xl mx-auto space-y-6">
                <?php if ($pesan_error != ""): ?><div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-sm font-bold rounded-xl"><?= $pesan_error; ?></div><?php endif; ?>
                <?php if ($pesan_sukses != ""): ?><div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold rounded-xl"><?= $pesan_sukses; ?></div><?php endif; ?>

                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($data_user['username']); ?>" required class="w-full text-sm rounded-xl border border-slate-200 p-3 mb-4">
                        <button type="submit" name="update_profil" class="w-full py-3 bg-slate-900 text-white font-bold text-xs rounded-xl">Simpan Username</button>
                    </form>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST" class="space-y-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase">Ganti Password</label>
                        <input type="password" name="pass_lama" placeholder="Password Lama" required class="w-full text-sm rounded-xl border border-slate-200 p-3">
                        <input type="password" name="pass_baru" placeholder="Password Baru" required class="w-full text-sm rounded-xl border border-slate-200 p-3">
                        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password Baru" required class="w-full text-sm rounded-xl border border-slate-200 p-3">
                        <button type="submit" name="update_password" class="w-full py-3 bg-indigo-600 text-white font-bold text-xs rounded-xl">Perbarui Kata Sandi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>