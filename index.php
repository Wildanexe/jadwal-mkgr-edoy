<?php
session_start();
require_once 'config/database.php';

// Proteksi halaman: Jika belum login, tendang balik ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// =========================================================
// QUERY UNTUK MENGAMBIL TOTAL DATA DARI DATABASE
// =========================================================

// 1. Hitung Total Guru
$query_guru = mysqli_query($db, "SELECT COUNT(id) as total FROM guru");
$data_guru = mysqli_fetch_assoc($query_guru);
$total_guru = $data_guru['total'] ?? 0;

// 2. Hitung Total Kelas (Rombel)
$query_kelas = mysqli_query($db, "SELECT COUNT(id) as total FROM kelas");
$data_kelas = mysqli_fetch_assoc($query_kelas);
$total_kelas = $data_kelas['total'] ?? 0;

// 3. Hitung Total Mapel
$query_mapel = mysqli_query($db, "SELECT COUNT(id) as total FROM mapel");
$data_mapel = mysqli_fetch_assoc($query_mapel);
$total_mapel = $data_mapel['total'] ?? 0;

// 4. Hitung Total Jadwal Aktif
$query_jadwal = mysqli_query($db, "SELECT COUNT(id) as total FROM jadwal");
$data_jadwal = mysqli_fetch_assoc($query_jadwal);
$total_jadwal = $data_jadwal['total'] ?? 0;

?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SMAS MKGR Sepatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50">

    <div class="flex min-h-screen">
        
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30">
            
            <div class="p-6 md:p-10 space-y-8 max-w-[1600px] mx-auto">
                
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
                    
                    <div class="xl:col-span-1 space-y-6">
                        <div>
                            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Ringkasan Eksekutif</h2>
                            <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                                Sistem Informasi Manajemen Jadwal Pengajaran SMAS MKGR Sepatan.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <div class="p-5 bg-white rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tenaga Pengajar</span>
                                <div class="flex items-baseline gap-1.5 mt-2">
                                    <span class="text-3xl font-black text-slate-900 tracking-tight"><?= $total_guru; ?></span>
                                    <span class="text-xs font-semibold text-slate-500">Orang</span>
                                </div>
                                <span class="inline-flex mt-4 px-2.5 py-0.5 text-[10px] font-bold bg-amber-50 text-amber-700 rounded-lg border border-amber-100">Kapasitas Aktif</span>
                            </div>

                            <div class="p-5 bg-white rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Rombongan Belajar</span>
                                <div class="flex items-baseline gap-1.5 mt-2">
                                    <span class="text-3xl font-black text-slate-900 tracking-tight"><?= $total_kelas; ?></span>
                                    <span class="text-xs font-semibold text-slate-500">Rombel</span>
                                </div>
                                <span class="inline-flex mt-4 px-2.5 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">Ruangan Terarsip</span>
                            </div>

                            <div class="p-5 bg-white rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Mata Pelajaran</span>
                                <div class="flex items-baseline gap-1.5 mt-2">
                                    <span class="text-3xl font-black text-slate-900 tracking-tight"><?= $total_mapel; ?></span>
                                    <span class="text-xs font-semibold text-slate-500">Mapel</span>
                                </div>
                                <span class="inline-flex mt-4 px-2.5 py-0.5 text-[10px] font-bold bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-100">Kurikulum Nasional</span>
                            </div>

                            <div class="p-5 bg-white rounded-2xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Jadwal Ter-Plot</span>
                                <div class="flex items-baseline gap-1.5 mt-2">
                                    <span class="text-3xl font-black text-slate-900 tracking-tight"><?= $total_jadwal; ?></span>
                                    <span class="text-xs font-semibold text-slate-500">Sesi</span>
                                </div>
                                <span class="inline-flex mt-4 px-2.5 py-0.5 text-[10px] font-bold bg-purple-50 text-purple-700 rounded-lg border border-purple-100">0 Konflik Terdeteksi</span>
                            </div>

                        </div>
                    </div>

                    <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8">
                        <div class="border-b border-slate-100 pb-5 mb-6">
                            <h3 class="text-lg font-bold text-slate-900 tracking-tight">Alur Validasi & Sinkronisasi Sistem</h3>
                            <p class="text-xs text-slate-400 mt-1">Langkah prosedural operasional mesin penjadwalan akademik.</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                            
                            <div class="space-y-3 p-4 rounded-2xl hover:bg-slate-50/50 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-900">1. Pendaftaran Entitas Master</h4>
                                <p class="text-xs text-slate-500 leading-relaxed font-light">
                                    Isi struktur fundamental akademik meliputi biodata Guru, pemetaan Rombel (Kelas), serta standarisasi Kurikulum Mata Pelajaran.
                                </p>
                            </div>
                            
                            <div class="space-y-3 p-4 rounded-2xl hover:bg-slate-50/50 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-900">2. Pembagian Slot Waktu</h4>
                                <p class="text-xs text-slate-500 leading-relaxed font-light">
                                    Sistem memetakan durasi kegiatan belajar mengajar secara digital untuk menghindari penulisan format jam manual yang berpotensi keliru.
                                </p>
                            </div>

                            <div class="space-y-3 p-4 rounded-2xl hover:bg-slate-50/50 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-900">3. Pemetaan & Proteksi Real-Time</h4>
                                <p class="text-xs text-slate-500 leading-relaxed font-light">
                                    Lakukan kompilasi jadwal. Mesin validasi database akan memblokir otomatis setiap keputusan penugasan guru atau ruangan yang bentrok.
                                </p>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="pt-6 border-t border-slate-200/50 text-center">
                    <p class="text-[11px] text-slate-400 font-medium">SMAS MKGR Sepatan - Hak Cipta Dilindungi Undang-Undang.</p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>