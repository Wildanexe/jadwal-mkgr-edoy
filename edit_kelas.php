<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: kelas.php");
    exit;
}
$id = mysqli_real_escape_string($db, $_GET['id']);

// Ambil data lama kelas dari database
$query = mysqli_query($db, "SELECT * FROM kelas WHERE id = '$id'");
if (mysqli_num_rows($query) === 0) {
    header("Location: kelas.php");
    exit;
}
$row = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $nama_kelas = mysqli_real_escape_string($db, $_POST['nama_kelas']);
    
    // Update murni kolom nama_kelas yang sudah terbukti ada di database kamu
    $update = mysqli_query($db, "UPDATE kelas SET nama_kelas='$nama_kelas' WHERE id='$id'");
    
    if ($update) {
        header("Location: kelas.php");
        exit;
    } else {
        echo "Gagal memperbarui data kelas: " . mysqli_error($db);
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas - SMAS MKGR Sepatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30">
            <div class="p-6 md:p-10 max-w-[600px] mx-auto space-y-6">
                
                <div class="flex items-center gap-3">
                    <a href="kelas.php" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Ubah Rombongan Belajar</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Edit nama identitas ruang kelas.</p>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Nama Rombel / Ruangan</label>
                            <input type="text" name="nama_kelas" value="<?= $row['nama_kelas']; ?>" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="kelas.php" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-all">Batal</a>
                            <button type="submit" name="update" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all">Simpan Pembaruan</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>
</html>