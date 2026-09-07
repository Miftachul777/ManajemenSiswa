<?php
include 'koneksi.php';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data_siswa.csv');
$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'NIS', 'Nama', 'Kelas', 'Alamat'));

$query = mysqli_query($conn, "SELECT * FROM siswa");
while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>