<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS siswa_todo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tugas VARCHAR(255) NOT NULL,
    status VARCHAR(50) DEFAULT 'Belum',
    pembuat VARCHAR(100) DEFAULT 'Administrator',
    selesai_oleh TEXT DEFAULT NULL
)");

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List & Agenda Bersama Administrator</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 30px; color: #1e293b; }
        .container { max-width: 1000px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05); }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; }
        .action-bar { display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
        .form-todo { display: flex; gap: 10px; flex: 1; min-width: 280px; }
        .form-todo input[type="text"], .search-form input[type="text"] { padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; }
        .form-todo input[type="text"] { flex: 1; }
        .search-form { display: flex; gap: 10px; }
        .btn { padding: 10px 16px; background-color: #6366f1; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-search { background-color: #4f46e5; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        th { background-color: #f1f5f9; }
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; display: inline-block; margin-bottom: 4px;}
        .badge-selesai { background-color: #d1fae5; color: #065f46; }
        .badge-belum { background-color: #fef3c7; color: #92400e; }
        .badge-pembuat { background-color: #e0e7ff; color: #3730a3; }
        .btn-action { padding: 5px 10px; border-radius: 6px; font-size: 12px; text-decoration: none; color: white; margin-right: 5px; display: inline-block; margin-bottom: 4px; }
        .btn-toggle { background-color: #10b981; }
        .btn-untoggle { background-color: #f59e0b; }
        .btn-danger { background-color: #ef4444; }
        .btn-info { background-color: #0ea5e9; }
        .empty-row { text-align: center; color: #64748b; font-style: italic; padding: 20px; }
        .worker-list { font-size: 12px; color: #475569; margin-top: 4px; }
        
        .modal { display: none; position: fixed; z-index: 100; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px; border-radius: 10px; }
        .close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .close:hover { color: black; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-flex">
            <h2>To-Do List & Agenda Bersama Administrator</h2>
        </div>

        <div class="action-bar">
            <form action="todo_aksi.php" method="POST" class="form-todo">
                <input type="text" name="tugas" placeholder="Tambahkan tugas atau agenda baru..." required autocomplete="off">
                <button type="submit" name="tambah_todo" class="btn">+ Tambah Tugas</button>
            </form>

            <form action="todo.php" method="GET" class="search-form">
                <input type="text" name="cari" placeholder="Cari tugas..." value="<?php echo htmlspecialchars($cari); ?>">
                <button type="submit" class="btn btn-search">Cari</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Daftar Tugas & Pembuat</th>
                    <th style="width: 180px;">Status Pengerjaan</th>
                    <th style="width: 180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM siswa_todo";
                if ($cari != '') {
                    $cari_esc = mysqli_real_escape_string($conn, $cari);
                    $query .= " WHERE tugas LIKE '%$cari_esc%'";
                }
                $query .= " ORDER BY id DESC";

                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $no = 1;
                    $current_user = $_SESSION['email'];

                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $pembuat = !empty($row['pembuat']) ? $row['pembuat'] : 'Administrator';
                        
                        $selesai_oleh = !empty($row['selesai_oleh']) ? json_decode($row['selesai_oleh'], true) : [];
                        if (!is_array($selesai_oleh)) {
                            $selesai_oleh = [];
                        }

                        $is_pembuat = ($current_user === $pembuat);
                        $sudah_dikerjakan = in_array($current_user, $selesai_oleh);
                        
                    
                        $total_selesai = count($selesai_oleh);
                        $worker_summary = $total_selesai > 0 ? "{$total_selesai} Orang Selesai" : "Belum ada";

                        echo "<tr>
                                <td>{$no}</td>
                                <td>
                                    <strong>" . htmlspecialchars($row['tugas']) . "</strong>
                                    <div style='font-size: 12px; color: #64748b; margin-top: 4px;'>Dibuat oleh: <strong>" . htmlspecialchars($pembuat) . "</strong></div>
                                </td>
                                <td>";
                        
                        if ($is_pembuat) {
                            echo "<span class='badge badge-pembuat'>Anda Pembuat (Pemantau)</span>";
                        } else {
                            if ($sudah_dikerjakan) {
                                echo "<span class='badge badge-selesai'>Sudah Anda Kerjakan</span>";
                            } else {
                                echo "<span class='badge badge-belum'>Belum Anda Kerjakan</span>";
                            }
                        }

                        echo "<div class='worker-list'><strong>Progres:</strong> {$worker_summary}</div>
                                </td>
                                <td>";
                        
                        
                        echo "<button onclick='openModal(\"modal_{$id}\")' class='btn-action btn-info'>Lihat Daftar</button>";
                        echo "<a href='todo_aksi.php?action=hapus&id={$id}' class='btn-action btn-danger' onclick='return confirm(\"Yakin ingin menghapus tugas ini?\")'>Hapus</a>
                                </td>
                              </tr>";

                        echo "<div id='modal_{$id}' class='modal'>
                                <div class='modal-content'>
                                    <span class='close' onclick='closeModal(\"modal_{$id}\")'>&times;</span>
                                    <h3>Daftar yang Sudah Mengerjakan</h3>
                                    <p style='font-size: 13px; color: #64748b;'>Tugas: <em>" . htmlspecialchars($row['tugas']) . "</em></p>
                                    <hr style='border:0; border-top:1px solid #e2e8f0; margin:10px 0;'>";
                        
                        if (!empty($selesai_oleh)) {
                            echo "<ul style='padding-left: 20px; font-size: 14px;'>";
                            foreach ($selesai_oleh as $worker) {
                                echo "<li>" . htmlspecialchars($worker) . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p style='color: #94a3b8; font-style: italic; font-size: 13px;'>Belum ada yang menandai tugas ini selesai.</p>";
                        }

                        echo "</div></div>";

                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='4' class='empty-row'>Tidak ada tugas atau agenda yang ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = "none";
            }
        }
    </script>
</body>
</html>