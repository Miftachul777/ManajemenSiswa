<?php
include 'koneksi.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nis              = mysqli_real_escape_string($conn, $_POST['nis']);
    $nama             = mysqli_real_escape_string($conn, $_POST['nama']);
    $email            = mysqli_real_escape_string($conn, $_POST['email']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $kelas            = mysqli_real_escape_string($conn, $_POST['kelas']);
    $jurusan          = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $alamat           = mysqli_real_escape_string($conn, $_POST['alamat']);

    if ($password !== $confirm_password) {
        $error = "Password dan Konfirmasi Password tidak cocok!";
    } else {
        $cek_email_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        
        if ($cek_email_users && mysqli_num_rows($cek_email_users) > 0) {
            $error = "Email sudah terdaftar, gunakan email lain!";
        } else {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO users (nis, nama, email, password, kelas, jurusan, alamat) 
                      VALUES ('$nis', '$nama', '$email', '$password_hashed', '$kelas', '$jurusan', '$alamat')";
            
            if (mysqli_query($conn, $query)) {
                echo "<script>
                        alert('Pendaftaran berhasil! Silakan login.'); 
                        window.location='login.php';
                      </script>";
                exit();
            } else {
                $error = "Terjadi kesalahan pada database: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran - Manajemen Siswa</title>
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-color: #f1f5f9;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: var(--text-main);
        }
        .register-container {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
            margin: 20px 0;
        }
        .register-container h2 {
            text-align: center;
            margin: 0 0 20px 0;
            color: var(--text-main);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
            outline: none;
            background-color: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .btn-submit {
            width: 100%;
            padding: 10px 16px;
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 5px;
        }
        .btn-submit:hover {
            background-color: var(--primary-hover);
        }
        .alert-error {
            background-color: #fef2f2;
            color: #ef4444;
            border: 1px solid #fee2e2;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-muted);
        }
        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <h2>Pendaftaran Akun</h2>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nis">NIS (Nomor Induk Siswa)</label>
                <input type="text" id="nis" name="nis" required placeholder="Masukkan NIS Anda">
            </div>

            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" required placeholder="Masukkan nama lengkap">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Masukkan email Anda">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Ulangi password">
            </div>

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

            <button type="submit" class="btn-submit">Daftar</button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>

</body>
</html>