<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include "koneksi.php";

$email_user = $_SESSION['email'];
$nama_user = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Administrator';

$jumlah_siswa = 0;
$jumlah_galeri = 0;
$jumlah_komentar = 0;
$jumlah_todo = 0;

if (isset($conn)) {
    $res_siswa = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa");
    if ($res_siswa) {
        $jumlah_siswa = mysqli_fetch_assoc($res_siswa)['total'];
    }

    $res_galeri = mysqli_query($conn, "SHOW TABLES LIKE 'galeri'");
    if ($res_galeri && mysqli_num_rows($res_galeri) > 0) {
        $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM galeri");
        if ($q) $jumlah_galeri = mysqli_fetch_assoc($q)['total'];
    }

    $res_komentar = mysqli_query($conn, "SHOW TABLES LIKE 'komentar'");
    if ($res_komentar && mysqli_num_rows($res_komentar) > 0) {
        $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM komentar");
        if ($q) $jumlah_komentar = mysqli_fetch_assoc($q)['total'];
    }

    $res_todo = mysqli_query($conn, "SHOW TABLES LIKE 'todo'");
    if ($res_todo && mysqli_num_rows($res_todo) > 0) {
        $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM todo");
        if ($q) $jumlah_todo = mysqli_fetch_assoc($q)['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Modern - Manajemen Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --primary-light: #eef2ff;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #f1f5f9;
            --border-card: #e2e8f0;
            --danger: #ef4444;
            --danger-bg: #fef2f2;
            --sidebar-width: 280px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        aside {
            width: var(--sidebar-width);
            background: var(--card-bg);
            border-right: 1px solid var(--border-card);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
        }

        .sidebar-brand {
            padding: 28px 24px;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-card);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand i {
            color: var(--primary);
            background: var(--primary-light);
            padding: 10px;
            border-radius: var(--radius-md);
            font-size: 18px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 24px 16px;
            margin: 0;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border-radius: var(--radius-md);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .sidebar-menu li a i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            transition: color 0.2s;
        }

        .sidebar-menu li a:hover {
            color: var(--primary);
            background: var(--primary-light);
        }

        .sidebar-menu li a.active {
            color: white;
            background: var(--primary);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .sidebar-menu li a.active i {
            color: white;
        }

        main {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            padding: 32px 40px;
            display: flex;
            flex-direction: column;
            max-width: calc(100vw - var(--sidebar-width));
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            background: var(--card-bg);
            padding: 20px 28px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-card);
            box-shadow: var(--shadow-sm);
        }

        .welcome-text h2 {
            margin: 0 0 4px 0;
            color: var(--text-main);
            font-size: 20px;
            font-weight: 700;
        }

        .welcome-text p {
            margin: 0;
            color: var(--text-muted);
            font-size: 13px;
        }

        .btn-logout {
            background-color: var(--danger-bg);
            color: var(--danger);
            border: 1px solid #fee2e2;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background-color: var(--danger);
            color: #ffffff;
            border-color: var(--danger);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .content-area {
            flex-grow: 1;
            background: var(--card-bg);
            border: 1px solid var(--border-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 520px;
            overflow: hidden;
            position: relative;
            padding: 40px;
        }

        .content-placeholder {
            text-align: left;
            width: 100%;
            max-width: 1000px;
        }

        .dashboard-banner {
            margin-bottom: 30px;
        }

        .dashboard-banner h3 {
            margin: 0 0 6px 0;
            color: var(--text-main);
            font-size: 24px;
            font-weight: 700;
        }

        .dashboard-banner > p {
            margin: 0;
            font-size: 14px;
            color: var(--text-muted);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            width: 100%;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-card);
            padding: 24px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.08);
            border-color: var(--primary);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-card h4 {
            margin: 0;
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .stat-card:nth-child(1) .stat-icon { background: #e0e7ff; color: #4f46e5; }
        .stat-card:nth-child(2) .stat-icon { background: #ccfbf1; color: #0d9488; }
        .stat-card:nth-child(3) .stat-icon { background: #e0f2fe; color: #0284c7; }
        .stat-card:nth-child(4) .stat-icon { background: #fef3c7; color: #d97706; }
        .stat-card:nth-child(5) .stat-icon { background: #dcfce7; color: #16a34a; }

        .stat-card .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
            letter-spacing: -0.5px;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            background: var(--card-bg);
        }

        @media (max-width: 768px) {
            aside { display: none; }
            main { margin-left: 0; padding: 20px; max-width: 100vw; }
        }
    </style>
    <script>
        function loadPage(url, element) {
            event.preventDefault();
            
            let menus = document.querySelectorAll('.sidebar-menu a');
            menus.forEach(menu => menu.classList.remove('active'));
            
            element.classList.add('active');

            document.getElementById('placeholder').style.display = 'none';
            let iframe = document.getElementById('content-frame');
            iframe.style.display = 'block';
            iframe.src = url;
        }

        function loadDashboard() {
            event.preventDefault();
            let menus = document.querySelectorAll('.sidebar-menu a');
            menus.forEach(menu => menu.classList.remove('active'));
            document.getElementById('dashboard-menu').classList.add('active');

            document.getElementById('content-frame').style.display = 'none';
            document.getElementById('content-frame').src = '';
            document.getElementById('placeholder').style.display = 'block';
        }
    </script>
</head>
<body>

    <aside>
        <div class="sidebar-brand">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Admin</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" id="dashboard-menu" class="active" onclick="loadDashboard()"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="siswa.php" onclick="loadPage('siswa.php', this)"><i class="fa-solid fa-users"></i> Data Siswa</a></li>
            <li><a href="galeri.php" onclick="loadPage('galeri.php', this)"><i class="fa-solid fa-images"></i> Galeri Foto</a></li>
            <li><a href="komentar.php" onclick="loadPage('komentar.php', this)"><i class="fa-solid fa-comments"></i> Komentar</a></li>
            <li><a href="todo.php" onclick="loadPage('todo.php', this)"><i class="fa-solid fa-list-check"></i> To-Do List</a></li>
        </ul>
    </aside>

    <main>
        <div class="dashboard-header">
            <div class="welcome-text">
                <h2>Selamat Datang, <?php echo htmlspecialchars($nama_user); ?>! 👋</h2>
                <p>Login sebagai: <strong><?php echo htmlspecialchars($email_user); ?></strong></p>
            </div>
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>

        <div class="content-area">
            <div id="placeholder" class="content-placeholder">
                <div class="dashboard-banner">
                    <h3>Ringkasan Statistik Sistem</h3>
                    <p>Pantau data keseluruhan aplikasi manajemen sekolah secara real-time di bawah ini.</p>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h4>Total Siswa</h4>
                            <div class="stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
                        </div>
                        <p class="stat-number"><?php echo $jumlah_siswa; ?></p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h4>Total Galeri</h4>
                            <div class="stat-icon"><i class="fa-solid fa-image"></i></div>
                        </div>
                        <p class="stat-number"><?php echo $jumlah_galeri; ?></p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h4>Total Komentar</h4>
                            <div class="stat-icon"><i class="fa-solid fa-comment-dots"></i></div>
                        </div>
                        <p class="stat-number"><?php echo $jumlah_komentar; ?></p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card-header">
                            <h4>To-Do List</h4>
                            <div class="stat-icon"><i class="fa-solid fa-clipboard-list"></i></div>
                        </div>
                        <p class="stat-number"><?php echo $jumlah_todo; ?></p>
                    </div>
                </div>
            </div>
            <iframe id="content-frame" name="contentFrame"></iframe>
        </div>
    </main>

</body>
</html>