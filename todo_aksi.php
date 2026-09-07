<?php
session_start();

include 'koneksi.php'; 

$tugas = $_POST['tugas'] ?? '';
$tugas_esc = mysqli_real_escape_string($conn, $tugas);

$query = "INSERT INTO siswa_todo (tugas, status, selesai_oleh) VALUES ('$tugas_esc', 'Belum', NULL)"; 

if (mysqli_query($conn, $query)) {
    header("Location: todo.php");
    exit();
} else {
    echo "Gagal menambahkan tugas: " . mysqli_error($conn);
}
?>