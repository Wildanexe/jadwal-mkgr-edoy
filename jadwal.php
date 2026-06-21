<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 1. Inisialisasi variabel pesan agar tidak error "Undefined variable"
$pesan_error = "";
$pesan_sukses = "";

// 2. PROSES PENYIMPANAN & VALIDASI ANTI-BENTROK
if (isset($_POST['submit_jadwal'])) {
    $hari = mysqli_real_escape_string($db, $_POST['hari']);
    $id_guru = mysqli_real_escape_string($db, $_POST['id_guru']);
    $id_kelas = mysqli_real_escape_string($db, $_POST['id_kelas']);
    $id_mapel = mysqli_real_escape_string($db, $_POST['id_mapel']);
    $jam_ke = mysqli_real_escape_string($db, $_POST['jam_ke']);

    // Cek apakah guru atau kelas sudah ada di jam yang sama
    $cek_bentrok = mysqli_query($db, "SELECT id FROM jadwal WHERE hari = '$hari' AND jam_ke = '$jam_ke' AND (id_guru = '$id_guru' OR id_kelas = '$id_kelas')");

    if (mysqli_num_rows($cek_bentrok) > 0) {
        $pesan_error = "⚠️ Gagal! Guru atau Ruang Kelas tersebut sudah memiliki jadwal di hari dan jam yang sama.";
    } else {
        // Query Insert (Tanpa kolom id karena sudah AUTO_INCREMENT)
        $simpan = mysqli_query($db, "INSERT INTO jadwal (hari, jam_ke, id_guru, id_kelas, id_mapel) 
                                    VALUES ('$hari', '$jam_ke', '$id_guru', '$id_kelas', '$id_mapel')");
        if ($simpan) {
            $pesan_sukses = "✅ Jadwal berhasil ditambahkan.";
        } else {
            $pesan_error = "Terjadi kesalahan database: " . mysqli_error($db);
        }
    }
}

// 3. Query untuk menampilkan daftar jadwal
$query = mysqli_query($db, "
    SELECT j.*, g.nama_guru, k.nama_kelas, m.nama_mapel 
    FROM jadwal j
    LEFT JOIN guru g ON j.id_guru = g.id 
    LEFT JOIN kelas k ON j.id_kelas = k.id 
    LEFT JOIN mapel m ON j.id_mapel = m.id 
    ORDER BY j.hari ASC, j.jam_ke ASC
");
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Jadwal - SMAS MKGR Sepatan</title>
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
            <div class="p-6 md:p-10 space-y-6 max-w-[1600px] mx-auto">

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Kompilasi Sesi Jadwal (Anti-Bentrok System)</h2>
                    <p class="text-xs text-slate-400 mt-1">Sistem otomatis menolak entri data baru jika mendeteksi adanya benturan waktu mengajar guru atau tabrakan ruang rombel.</p>
                </div>

                <?php if ($pesan_error != ""): ?>
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs font-bold text-rose-700 transition-all"><?= $pesan_error; ?></div>
                <?php endif; ?>
                <?php if ($pesan_sukses != ""): ?>
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-700 transition-all"><?= $pesan_sukses; ?></div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                    <div class="lg:col-span-1 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Plot Alokasi Waktu</h3>

                        <form action="" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1.5">Hari Operasional</label>
                                <select name="hari" required class="block w-full text-xs rounded-xl border border-slate-200 p-3 bg-slate-50 font-semibold text-slate-700 outline-none">
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1.5">Jam Pelajaran & Durasi</label>
                                <select name="jam_ke" required class="block w-full text-xs rounded-xl border border-slate-200 p-3 bg-slate-50 font-semibold text-slate-700 outline-none focus:bg-white focus:border-slate-900">
                                    <option value="1">Jam Ke-1 (07:15 - 08:00 WIB)</option>
                                    <option value="2">Jam Ke-2 (08:00 - 08:45 WIB)</option>
                                    <option value="3">Jam Ke-3 (08:45 - 09:30 WIB)</option>
                                    <option value="4">Jam Ke-4 (09:45 - 10:30 WIB)</option>
                                    <option value="5">Jam Ke-5 (10:30 - 11:15 WIB)</option>
                                    <option value="6">Jam Ke-6 (11:15 - 12:00 WIB)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1.5">Guru Pengampu</label>
                                <select name="id_guru" required class="block w-full text-xs rounded-xl border border-slate-200 p-3 bg-slate-50 font-semibold text-slate-700 outline-none focus:bg-white focus:border-slate-900">
                                    <option value="" disabled selected>-- Pilih Guru --</option>
                                    <?php 
                                    $q_guru = mysqli_query($db, "SELECT * FROM guru ORDER BY nama_guru ASC");
                                    while($g = mysqli_fetch_assoc($q_guru)) {
                                        echo "<option value='".$g['id']."'>".$g['nama_guru']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1.5">Ruang Rombel</label>
                                <select name="id_kelas" required class="block w-full text-xs rounded-xl border border-slate-200 p-3 bg-slate-50 font-semibold text-slate-700 outline-none focus:bg-white focus:border-slate-900">
                                    <option value="" disabled selected>-- Pilih Ruang Rombel --</option>
                                    <?php 
                                    $q_kelas = mysqli_query($db, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                                    while($k = mysqli_fetch_assoc($q_kelas)) {
                                        echo "<option value='".$k['id']."'>".$k['nama_kelas']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1.5">Mata Pelajaran</label>
                                <select name="id_mapel" required class="block w-full text-xs rounded-xl border border-slate-200 p-3 bg-slate-50 font-semibold text-slate-700 outline-none focus:bg-white focus:border-slate-900">
                                    <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                                    <?php 
                                    $q_mapel = mysqli_query($db, "SELECT * FROM mapel ORDER BY nama_mapel ASC");
                                    while($m = mysqli_fetch_assoc($q_mapel)) {
                                        echo "<option value='".$m['id']."'>".$m['nama_mapel']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit" name="submit_jadwal" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                                Validasi & Terbitkan Sesi
                            </button>
                        </form>
                    </div>

                    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="p-4 bg-slate-50/60 border-b border-slate-100">
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Daftar Jadwal Pengajaran Aktif</h3>
                        </div>
                        <div class="overflow-x-auto w-full">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/30 border-b border-slate-100">
                                        <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center w-12">No</th>
                                        <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-24">Waktu</th>
                                        <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sesi Pengajaran</th>
                                        <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-24 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                                    <?php
                                    $no = 1;
                                    if (mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_assoc($query)): ?>
                                            <tr class="hover:bg-slate-50/40 transition-colors">
                                                <td class="p-4 text-center text-slate-400"><?= $no++; ?></td>
                                                <td class="p-4 space-y-1">
                                                    <span class="px-2 py-0.5 bg-slate-100 rounded text-[10px] font-bold text-slate-800 block text-center"><?= $row['hari']; ?></span>
                                                    <span class="text-[10px] font-semibold text-indigo-600 block text-center">Jam Ke-<?= $row['jam_ke']; ?></span>
                                                </td>
                                                <td class="p-4 space-y-0.5">
                                                    <p class="text-sm font-bold text-slate-900"><?= $row['nama_mapel'] ?? '<span class="text-rose-500">Terhapus</span>'; ?> — <span class="text-slate-500"><?= $row['nama_kelas'] ?? '<span class="text-rose-500">Terhapus</span>'; ?></span></p>
                                                    <p class="text-slate-400 text-[11px]"><?= $row['nama_guru'] ?? '<span class="text-rose-500">Terhapus</span>'; ?></p>
                                                </td>
                                                <td class="p-4 text-center">
                                                    <div class="flex items-center justify-center gap-2">
                                                        <a href="edit_jadwal.php?id=<?= $row['id']; ?>" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg inline-block transition-all" title="Edit Sesi">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        </a>
                                                        <a href="hapus_jadwal.php?id=<?= $row['id']; ?>" onclick="return confirm('Batalkan sesi ini?')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg inline-block transition-all" title="Hapus Sesi">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; 
                                    } else { ?>
                                        <tr>
                                            <td colspan="4" class="p-8 text-center text-slate-400">Belum ada sesi jadwal yang terdaftar.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</body>

</html>