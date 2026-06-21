<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$error_msg = "";

if (isset($_POST['submit'])) {
    $kode = trim(mysqli_real_escape_string($db, $_POST['kode_mapel']));
    $nama_mapel = trim(mysqli_real_escape_string($db, $_POST['nama_mapel']));
    $kelompok = mysqli_real_escape_string($db, $_POST['kelompok']);

    // Cek duplikasi kode mapel
    $cek_ganda = mysqli_query($db, "SELECT id FROM mapel WHERE kode_mapel = '$kode'");
    if (mysqli_num_rows($cek_ganda) > 0) {
        $error_msg = "Gagal! Kode Mapel <strong>'" . htmlspecialchars($kode) . "'</strong> sudah digunakan oleh mapel lain.";
    } else {
        // Simpan menggunakan susunan nama kolom database yang aman
        // Ganti query insert di tambah_mapel.php menjadi:
        $insert = mysqli_query($db, "INSERT INTO mapel (kode_mapel, nama_mapel, kelompok) VALUES ('$kode', '$nama_mapel', '$kelompok')");
        if ($insert) {
            header("Location: mapel.php");
            exit;
        } else {
            $error_msg = "Terjadi kesalahan sistem: " . mysqli_error($db);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mapel - SMAS MKGR Sepatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30">
            <div class="p-6 md:p-10 max-w-[600px] mx-auto space-y-6">

                <div class="flex items-center gap-3">
                    <a href="mapel.php" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Tambah Mata Pelajaran</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Daftarkan mata pelajaran kurikulum baru.</p>
                    </div>
                </div>

                <?php if (!empty($error_msg)) : ?>
                    <div class="p-4 text-sm text-rose-700 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div><?= $error_msg; ?></div>
                    </div>
                <?php endif; ?>

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Kode Mapel</label>
                            <input type="text" name="kode_mapel" placeholder="Contoh: MP-001" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Nama Mata Pelajaran</label>
                            <input type="text" name="nama_mapel" placeholder="Contoh: Matematika Wajib" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Kelompok Kurikulum</label>
                            <select name="kelompok" class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-semibold text-slate-700">
                                <option value="Wajib">Kelompok A (Wajib Nasional)</option>
                                <option value="Peminatan">Kelompok B (Peminatan Internal)</option>
                                <option value="Muatan Lokal">Kelompok C (Muatan Lokal)</option>
                            </select>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="mapel.php" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-all">Batal</a>
                            <button type="submit" name="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all">Simpan Mapel</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>

</html>