<?php
session_start();

include "koneksi.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$role_user = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
$nama_user = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'User';

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$filter_kelas = isset($_GET['kelas']) ? trim($_GET['kelas']) : '';
$filter_jurusan = isset($_GET['jurusan']) ? trim($_GET['jurusan']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Manajemen Siswa</title>
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-color: #f1f5f9;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --danger: #ef4444;
            --danger-hover: #dc2626;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 40px;
            color: var(--text-main);
        }

        .container {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            max-width: 1200px;
            margin: 0 auto;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        h2 {
            margin: 0 0 5px 0;
            color: var(--text-main);
        }

        p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: inline-block;
            transition: background 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .notice-siswa {
            background: #eef2ff;
            color: #4f46e5;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .filter-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-bar input[type="text"], .filter-bar select, .form-group select {
            padding: 9px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background-color: #fff;
            color: var(--text-main);
        }

        .filter-bar input[type="text"] {
            flex: 1;
            min-width: 200px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group select {
            width: 100%;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            min-width: 800px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        th {
            background-color: #f8fafc;
            color: var(--text-main);
            font-weight: 600;
        }

        tr:hover {
            background-color: #f8fafc;
        }

        .action-links a {
            text-decoration: none;
            margin-right: 10px;
            font-weight: 500;
            font-size: 13px;
        }

        .link-edit {
            color: #2563eb;
        }

        .link-hapus {
            color: var(--danger);
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-section">
            <div>
                <h2>Data Siswa</h2>
                <p>Selamat datang, <strong><?php echo htmlspecialchars($nama_user); ?></strong> (Role: <?php echo ucfirst($role_user); ?>)</p>
            </div>
            
            <div>
                <?php if ($role_user === 'admin'): ?>
                    <a href="tambah_siswa.php" class="btn-primary">+ Tambah Siswa</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($role_user !== 'admin'): ?>
            <div class="notice-siswa">
                <em>ℹ️ Anda masuk sebagai siswa. Anda hanya memiliki akses untuk melihat data siswa.</em>
            </div>
        <?php endif; ?>

        <form method="GET" action="" class="filter-bar">
            <input type="text" name="keyword" placeholder="Cari berdasarkan nama siswa..." value="<?php echo htmlspecialchars($keyword); ?>">
            
            <select name="kelas">
                <option value="">-- Pilih Kelas --</option>
                <option value="X A" <?php echo ($filter_kelas == 'X A') ? 'selected' : ''; ?>>X A</option>
                <option value="X B" <?php echo ($filter_kelas == 'X B') ? 'selected' : ''; ?>>X B</option>
                <option value="X C" <?php echo ($filter_kelas == 'X C') ? 'selected' : ''; ?>>X C</option>
                <option value="X D" <?php echo ($filter_kelas == 'X D') ? 'selected' : ''; ?>>X D</option>
                <option value="XI A" <?php echo ($filter_kelas == 'XI A') ? 'selected' : ''; ?>>XI A</option>
                <option value="XI B" <?php echo ($filter_kelas == 'XI B') ? 'selected' : ''; ?>>XI B</option>
                <option value="XI C" <?php echo ($filter_kelas == 'XI C') ? 'selected' : ''; ?>>XI C</option>
                <option value="XI D" <?php echo ($filter_kelas == 'XI D') ? 'selected' : ''; ?>>XI D</option>
                <option value="XII A" <?php echo ($filter_kelas == 'XII A') ? 'selected' : ''; ?>>XII A</option>
                <option value="XII B" <?php echo ($filter_kelas == 'XII B') ? 'selected' : ''; ?>>XII B</option>
                <option value="XII C" <?php echo ($filter_kelas == 'XII C') ? 'selected' : ''; ?>>XII C</option>
                <option value="XII D" <?php echo ($filter_kelas == 'XII D') ? 'selected' : ''; ?>>XII D</option>
            </select>

            <select name="jurusan">
                <option value="">-- Pilih Jurusan --</option>
                <option value="TKR" <?php echo ($filter_jurusan == 'TKR') ? 'selected' : ''; ?>>TKR</option>
                <option value="TJKT" <?php echo ($filter_jurusan == 'TJKT') ? 'selected' : ''; ?>>TJKT</option>
                <option value="FARMASI" <?php echo ($filter_jurusan == 'FARMASI') ? 'selected' : ''; ?>>FARMASI</option>
                <option value="PPLG" <?php echo ($filter_jurusan == 'PPLG') ? 'selected' : ''; ?>>PPLG</option>
            </select>

            <button type="submit" class="btn-primary">Filter</button>
            <?php if (!empty($keyword) || !empty($filter_kelas) || !empty($filter_jurusan)): ?>
                <a href="data_siswa.php" class="btn-primary" style="background-color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
            <?php endif; ?>
        </form>

        <div style="display: none;">
            <div class="form-group">
                <label for="kelas">Kelas</label>
                <select id="kelas" name="kelas" required>
                    <option value="" disabled selected>Pilih Kelas</option>
                    <option value="X A">X A</option>
                    <option value="X B">X B</option>
                    <option value="X C">X C</option>
                    <option value="X D">X D</option>
                    <option value="XI A">XI A</option>
                    <option value="XI B">XI B</option>
                    <option value="XI C">XI C</option>
                    <option value="XI D">XI D</option>
                    <option value="XII A">XII A</option>
                    <option value="XII B">XII B</option>
                    <option value="XII C">XII C</option>
                    <option value="XII D">XII D</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <select id="jurusan" name="jurusan" required>
                    <option value="" disabled selected>Pilih Jurusan</option>
                    <option value="TKR">TKR</option>
                    <option value="TJKT">TJKT</option>
                    <option value="FARMASI">FARMASI</option>
                    <option value="PPLG">PPLG</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Alamat</th>
                        <!-- Kolom Aksi hanya muncul untuk admin -->
                        <?php if ($role_user === 'admin'): ?>
                            <th>Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM siswa WHERE 1=1";

                    if (!empty($keyword)) {
                        $keyword_safe = mysqli_real_escape_string($conn, $keyword);
                        $query .= " AND nama LIKE '%$keyword_safe%'";
                    }

                    if (!empty($filter_kelas)) {
                        $kelas_safe = mysqli_real_escape_string($conn, $filter_kelas);
                        $query .= " AND kelas = '$kelas_safe'";
                    }

                    if (!empty($filter_jurusan)) {
                        $jurusan_safe = mysqli_real_escape_string($conn, $filter_jurusan);
                        $query .= " AND jurusan = '$jurusan_safe'";
                    }

                    $query .= " ORDER BY nama ASC";
                    $result = mysqli_query($conn, $query);
                    $no = 1;
                    
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['nis']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['jurusan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";

                            // Tombol Edit & Hapus disembunyikan jika role bukan admin
                            if ($role_user === 'admin') {
                                echo "<td class='action-links'>
                                        <a href='edit_siswa.php?id=" . $row['id'] . "' class='link-edit'>Edit</a> 
                                        <a href='hapus_siswa.php?id=" . $row['id'] . "' class='link-hapus' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                                      </td>";
                            }

                            echo "</tr>";
                        }
                    } else {
                        $colspan = ($role_user === 'admin') ? 8 : 7;
                        echo "<tr><td colspan='$colspan' align='center' style='padding: 20px; color: var(--text-muted);'>Tidak ada data siswa yang ditemukan.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>