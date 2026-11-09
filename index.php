<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: bilik_suara.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($koneksi, trim($_POST['name']));
    $password_input = $_POST['password']; // Ini adalah NISN / NIP yang diketik pengguna

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE name = '$name'");
    
    if (mysqli_num_rows($query) === 1) {
        $user = mysqli_fetch_assoc($query);
        
        // Verifikasi Hash Password (NISN/NIP)
        if (password_verify($password_input, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['class'] = $user['class'];
            
            header("Location: bilik_suara.php");
            exit;
        } else {
            $error = "NISN/NIP tidak valid!";
        }
    } else {
        $error = "Nama Lengkap tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-VOSIS SMAPER</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md overflow-hidden">
        <div class="bg-slate-900 p-6 text-center">
            <h1 class="text-2xl font-bold text-yellow-400">E-VOSIS SMAPER</h1>
            <p class="text-slate-300 text-sm mt-1">SMA Perkappen Sinumbra</p>
        </div>
        <div class="p-6">
            <?php if($error): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center">
                    <?= $error; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-slate-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-slate-900 uppercase" placeholder="Masukkan Nama Lengkap">
                </div>
                <div class="mb-6">
                    <label class="block text-slate-700 text-sm font-bold mb-2">NISN / NIP (Password)</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-slate-900" placeholder="Masukkan NISN atau NIP">
                </div>
                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-slate-900 font-bold py-2 px-4 rounded-lg transition duration-200">
                    Masuk Bilik Suara
                </button>
            </form>
        </div>
    </div>
</body>
</html>
