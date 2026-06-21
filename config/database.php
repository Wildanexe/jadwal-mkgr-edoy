<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "db_jadwal_mkgr";

// Menggunakan koneksi MySQLi Procedural agar serasi dengan mysqli_query
$db = mysqli_connect($host, $username, $password, $database);

// Cek apakah koneksi berhasil atau gagal
if (!$db) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>