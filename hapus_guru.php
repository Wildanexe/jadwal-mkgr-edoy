<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah ada ID yang dikirim
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);

    // Jalankan query hapus asli ke database
    $delete = mysqli_query($db, "DELETE FROM guru WHERE id = '$id'");
    
    if (!$delete) {
        echo "Gagal menghapus data: " . mysqli_error($db);
        exit;
    }
}

// Setelah sukses menghapus, langsung lempar balik ke halaman utama guru
header("Location: guru.php");
exit;
?>