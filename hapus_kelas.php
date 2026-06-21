<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$delete = mysqli_query($db, "DELETE FROM kelas WHERE id = '$id'");

header("Location: kelas.php");
exit;
?>