<?php
session_start();
include 'koneksi.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result_admin = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");
    
    if (mysqli_num_rows($result_admin) === 1) {
        $row = mysqli_fetch_assoc($result_admin);
        
        if (password_verify($password, $row['password']) || $password == $row['password']) {
            $_SESSION['id']          = $row['id'];
            $_SESSION['email']       = $row['email'];
            $_SESSION['nama']        = $row['nama'];
            $_SESSION['foto_profil'] = $row['foto_profil'] ?? '';
            $_SESSION['alamat']      = $row['alamat'] ?? '';
            $_SESSION['role']        = strtolower($row['level'] ?? 'admin');
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        $result_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        
        if (mysqli_num_rows($result_users) === 1) {
            $row = mysqli_fetch_assoc($result_users);
            
            if (password_verify($password, $row['password']) || $password == $row['password']) {
                $_SESSION['id']      = $row['id'];
                $_SESSION['email']   = $row['email'];
                $_SESSION['nama']    = $row['nama'];
                $_SESSION['nisn']    = $row['nisn'];
                $_SESSION['kelas']   = $row['kelas'];
                $_SESSION['jurusan'] = $row['jurusan'];
                $_SESSION['alamat']  = $row['alamat'];
                $_SESSION['role']    = 'siswa';
                
                header("Location: siswa/dashboard_siswa.php");
                exit();
            } else {
                $error = "Password yang Anda masukkan salah!";
            }
        } else {
            $error = "Email tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Siswa</title>
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --danger-bg: #fee2e2;
            --danger-text: #dc2626;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            box-sizing: border-box;
        }

        .login-container {
            background: var(--card-bg);
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 25px;
            color: var(--text-main);
            font-size: 24px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 14px;
            color: var(--text-main);
            background-color: #f8fafc;
            transition: all 0.2s;
        }

        .form-group input:focus {
            border-color: var(--primary);
            outline: none;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .alert-error {
            background-color: var(--danger-bg);
            color: var(--danger-text);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            font-weight: 500;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Masuk Akun</h2>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Masukkan email Anda" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password Anda">
            </div>
            <button type="submit" class="btn-submit">Login</button>
        </form>

        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>

</body>
</html>