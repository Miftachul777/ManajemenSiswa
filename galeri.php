<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$pesan = "";
$tipe_pesan = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $jumlah_file = count($_FILES['foto']['name']);
    $berhasil = 0;

    for ($i = 0; $i < $jumlah_file; $i++) {
        if ($_FILES['foto']['error'][$i] == 0) {
            $nama_file = basename($_FILES['foto']['name'][$i]);
            $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
            
            $ekstensi_boleh = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($ekstensi, $ekstensi_boleh)) {
                $nama_baru = uniqid() . '.' . $ekstensi;
                $target_file = $target_dir . $nama_baru;

                if (move_uploaded_file($_FILES['foto']['tmp_name'][$i], $target_file)) {
                    $query = "INSERT INTO galeri (nama_file) VALUES ('$nama_baru')";
                    mysqli_query($conn, $query);
                    $berhasil++;
                }
            }
        }
    }

    if ($berhasil > 0) {
        $pesan = "Berhasil mengunggah $berhasil foto!";
        $tipe_pesan = "success";
    } else {
        $pesan = "Gagal mengunggah foto. Pastikan format file adalah JPG, JPEG, PNG, atau GIF.";
        $tipe_pesan = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Foto - Manajemen Siswa</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --success-bg: #dcfce7;
            --success-text: #166534;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 30px;
            box-sizing: border-box;
            color: var(--text-main);
        }

        .container {
            max-width: 950px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }

        .header-flex h2 {
            margin: 0;
            font-size: 22px;
            color: var(--text-main);
        }

        .upload-box {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 2px dashed var(--border-color);
            text-align: center;
        }

        .upload-box input[type="file"] {
            margin-bottom: 10px;
        }

        .btn {
            padding: 10px 20px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn:hover {
            background-color: var(--primary-hover);
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }

        .alert.success {
            background-color: var(--success-bg);
            color: var(--success-text);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .gallery-item {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
        }

        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }

        .empty-gallery {
            text-align: center;
            color: var(--text-muted);
            font-style: italic;
            padding: 30px;
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-flex">
            <h2>Galeri Foto Kegiatan</h2>
        </div>

        <?php if (!empty($pesan)): ?>
            <div class="alert <?php echo $tipe_pesan; ?>"><?php echo $pesan; ?></div>
        <?php endif; ?>

        <div class="upload-box">
            <form action="" method="POST" enctype="multipart/form-data">
                <p style="margin-top: 0; font-size: 14px; color: var(--text-muted);">Pilih satu atau beberapa foto sekaligus (JPG, PNG, GIF):</p>
                <input type="file" name="foto[]" multiple accept="image/*" required>
                <br>
                <button type="submit" class="btn">Upload Foto</button>
            </form>
        </div>

        <div class="gallery-grid">
            <?php
            $cek_tabel = mysqli_query($conn, "SHOW TABLES LIKE 'galeri'");
            if (mysqli_num_rows($cek_tabel) > 0) {
                $result = mysqli_query($conn, "SELECT * FROM galeri ORDER BY id DESC");
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="gallery-item">
                                <img src="uploads/' . htmlspecialchars($row['nama_file']) . '" alt="Foto Galeri">
                              </div>';
                    }
                } else {
                    echo '<div class="empty-gallery">Belum ada foto yang diunggah.</div>';
                }
            } else {
                echo '<div class="empty-gallery">Tabel database "galeri" belum dibuat.</div>';
            }
            ?>
        </div>
    </div>

</body>
</html>