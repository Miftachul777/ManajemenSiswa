<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$pesan_error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis     = trim($_POST['nis']);
    $nama    = trim($_POST['nama']);
    $email   = trim($_POST['email']);
    $kelas   = trim($_POST['kelas']);
    $jurusan = trim($_POST['jurusan']);
    $alamat  = trim($_POST['alamat']);

    if (!empty($nis) && !empty($nama) && !empty($email) && !empty($kelas) && !empty($jurusan)) {
        $cek_email = mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'");
        if ($cek_email && mysqli_num_rows($cek_email) > 0) {
            $pesan_error = "Email sudah terdaftar, gunakan email lain!";
        } else {
            $nis_esc     = mysqli_real_escape_string($conn, $nis);
            $nama_esc    = mysqli_real_escape_string($conn, $nama);
            $email_esc   = mysqli_real_escape_string($conn, $email);
            $password_default = password_hash('123456', PASSWORD_DEFAULT);
            $kelas_esc   = mysqli_real_escape_string($conn, $kelas);
            $jurusan_esc = mysqli_real_escape_string($conn, $jurusan);
            $alamat_esc  = mysqli_real_escape_string($conn, $alamat);

            $query = "INSERT INTO siswa (nis, nama, email, password, role, kelas, jurusan, alamat) VALUES ('$nis_esc', '$nama_esc', '$email_esc', '$password_default', 'siswa', '$kelas_esc', '$jurusan_esc', '$alamat_esc')";
            
            if (mysqli_query($conn, $query)) {
                header("Location: siswa.php");
                exit();
            } else {
                $pesan_error = "Gagal menyimpan ke database: " . mysqli_error($conn);
            }
        }
    } else {
        $pesan_error = "Kolom wajib bertanda bintang (*) harus diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - Manajemen Siswa</title>
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --success: #6366f1;
            --success-hover: #4f46e5;
            --bg-color: #f1f5f9;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --danger: #ef4444;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            padding: 40px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background: var(--card-bg);
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            margin-top: 0;
            color: var(--text-main);
            font-size: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }
        input[type="text"], input[type="email"], select, textarea {
            padding: 9px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 13px;
            outline: none;
            background-color: #fff;
            font-family: inherit;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .alert-error {
            background-color: #fee2e2;
            color: var(--danger);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            margin-bottom: 15px;
        }
        .info-password {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: -2px;
            margin-bottom: 5px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 9px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            display: inline-block;
            text-align: center;
        }
        .btn-submit {
            background-color: var(--success);
            color: white;
            flex: 1;
        }
        .btn-submit:hover {
            background-color: var(--success-hover);
        }
        .btn-cancel {
            background-color: #e2e8f0;
            color: var(--text-main);
        }
        .btn-cancel:hover {
            background-color: #cbd5e1;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Tambah Data Siswa Baru</h2>

        <?php if (!empty($pesan_error)): ?>
            <div class="alert-error"><?php echo $pesan_error; ?></div>
        <?php endif; ?>

        <form action="tambah_siswa.php" method="POST">
            <div class="form-group">
                <label for="nis">NIS *</label>
                <input type="text" id="nis" name="nis" placeholder="Contoh: 102938" required>
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama" placeholder="Contoh: Muhamad Miftachul Ulum" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" placeholder="Contoh: Mxxx@gmail.com" required>
                <span class="info-password">Password otomatis untuk siswa baru adalah: <strong>123456</strong></span>
            </div>

            <div class="form-group">
                <label for="kelas">Kelas *</label>
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
                <label for="jurusan">Jurusan *</label>
                <select id="jurusan" name="jurusan" required>
                    <option value="" disabled selected>Pilih Jurusan</option>
                    <option value="TKR">TKR</option>
                    <option value="TJKT">TJKT</option>
                    <option value="FARMASI">FARMASI</option>
                    <option value="PPLG">PPLG</option>
                </select>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-submit">Simpan Data</button>
                <a href="siswa.php" class="btn btn-cancel">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>