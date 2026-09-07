<?php
include 'koneksi.php';

if (isset($_POST['kirim'])) {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama']);
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    
    if(!empty($nama) && !empty($pesan)) {
        mysqli_query($conn, "INSERT INTO komentar (nama, isi_pesan) VALUES ('$nama', '$pesan')");
        header("Location: komentar.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Komentar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .komentar-container {
            background: #ffffff;
            padding: 25px 35px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 600px;
        }
        .komentar-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }
        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
            font-family: inherit;
        }
        .form-group input:focus, 
        .form-group textarea:focus {
            border-color: #4338ca;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
        .form-group textarea {
            resize: vertical;
            height: 100px;
        }
        .btn-submit {
            width: 100%;
            padding: 11px;
            background-color: #4f46e5;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background-color: #4f46e5;
        }
        hr {
            border: 0;
            border-top: 1px solid #eaeaea;
            margin: 25px 0;
        }
        h3 {
            color: #444;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .comment-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 5px;
        }
        .comment-card {
            background: #fafafa;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .comment-name {
            font-weight: bold;
            color: #1f2937;
            font-size: 14px;
        }
        .comment-time {
            color: #9ca3af;
            font-size: 12px;
        }
        .comment-body {
            color: #4b5563;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            word-break: break-word;
        }
        .empty-text {
            color: #777;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>

    <div class="komentar-container">
        <h2>Halaman Komentar</h2>
        
        <form method="POST">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" required placeholder="Masukkan nama Anda">
            </div>
            <div class="form-group">
                <label for="pesan">Isi Pesan</label>
                <textarea id="pesan" name="pesan" required placeholder="Tulis komentar atau pesan Anda di sini..."></textarea>
            </div>
            <button type="submit" name="kirim" class="btn-submit">Kirim Pesan</button>
        </form>
        
        <hr>
        
        <h3>Daftar Komentar</h3>
        <div class="comment-list">
            <?php
            $komentar = mysqli_query($conn, "SELECT * FROM komentar ORDER BY id DESC");
            if (mysqli_num_rows($komentar) > 0) {
                while($k = mysqli_fetch_assoc($komentar)){
                    echo "<div class='comment-card'>
                            <div class='comment-header'>
                                <span class='comment-name'>" . htmlspecialchars($k['nama']) . "</span>
                                <span class='comment-time'>" . $k['waktu_kirim'] . "</span>
                            </div>
                            <p class='comment-body'>" . nl2br(htmlspecialchars($k['isi_pesan'])) . "</p>
                          </div>";
                }
            } else {
                echo "<p class='empty-text'>Belum ada komentar yang dikirim.</p>";
            }
            ?>
        </div>
    </div>

</body>
</html>