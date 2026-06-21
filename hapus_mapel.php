<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    
    $delete = mysqli_query($db, "DELETE FROM mapel WHERE id = '$id'");
    
    if (!$delete) {
        echo "Gagal menghapus data mapel: " . mysqli_error($db);
        exit;
    }
}

header("Location: mapel.php");
exit;
?>