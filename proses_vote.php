<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$candidate_id = intval($_POST['candidate_id']);

// 1. Validasi apakah benar-benar belum memilih
$query_cek = mysqli_query($koneksi, "SELECT has_voted FROM users WHERE id = '$user_id'");
$data_user = mysqli_fetch_assoc($query_cek);

if ($data_user['has_voted'] == 1) {
    die("Akses ditolak: Anda sudah memilih.");
}

// 2. Mulai Transaksi untuk Keamanan Data
mysqli_begin_transaction($koneksi);

try {
    // A. Masukkan suara ke kotak suara (ANONIM - Hanya candidate_id yang dikirim)
    $stmt_vote = mysqli_prepare($koneksi, "INSERT INTO votes (candidate_id) VALUES (?)");
    mysqli_stmt_bind_param($stmt_vote, "i", $candidate_id);
    mysqli_stmt_execute($stmt_vote);

    // B. Kunci akun pemilih agar tidak bisa memilih lagi
    $stmt_user = mysqli_prepare($koneksi, "UPDATE users SET has_voted = 1 WHERE id = ?");
    mysqli_stmt_bind_param($stmt_user, "i", $user_id);
    mysqli_stmt_execute($stmt_user);

    // Jika keduanya berhasil tanpa error, eksekusi permanen (Commit)
    mysqli_commit($koneksi);
    
    $_SESSION['has_voted'] = 1;
    header("Location: bilik_suara.php");
    exit;
} catch (Exception $e) {
    // Jika terjadi error di salah satu proses, batalkan semua (Rollback)
    mysqli_rollback($koneksi);
    die("Terjadi kesalahan pada sistem. Suara dibatalkan.");
}
?>
