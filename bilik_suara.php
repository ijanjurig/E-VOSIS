<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query_user = mysqli_query($koneksi, "SELECT has_voted FROM users WHERE id = '$user_id'");
$data_user = mysqli_fetch_assoc($query_user);
$sudah_memilih = $data_user['has_voted'];

$kandidat = mysqli_query($koneksi, "SELECT * FROM candidates ORDER BY nomor_urut ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilik Suara - E-VOSIS SMAPER</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">
    <!-- Navbar -->
    <nav class="bg-slate-900 p-4 shadow-md flex justify-between items-center text-white">
        <div class="font-bold text-yellow-400">E-VOSIS</div>
        <div class="text-sm text-right">
            <div><?= htmlspecialchars($_SESSION['name']); ?></div>
            <div class="text-slate-400 text-xs"><?= htmlspecialchars($_SESSION['class']); ?></div>
        </div>
    </nav>

    <div class="container mx-auto p-4 max-w-4xl mt-6">
        <?php if ($sudah_memilih): ?>
            <!-- Tampilan Bukti Partisipasi -->
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-8 rounded-lg text-center shadow-sm">
                <h2 class="text-2xl font-bold mb-2">Terima Kasih!</h2>
                <p>Hak suara Anda telah digunakan dan dicatat secara anonim ke dalam sistem.</p>
                <a href="logout.php" class="inline-block mt-4 bg-slate-900 text-white px-6 py-2 rounded shadow hover:bg-slate-800">Keluar Sistem</a>
            </div>
        <?php else: ?>
            <!-- Tampilan Kandidat -->
            <div class="text-center mb-8">
                <h2 class="text-xl font-bold text-slate-800">Pilih Pasangan Calon</h2>
                <p class="text-slate-500 text-sm">Gunakan hak suara Anda dengan bijak. Pilihan bersifat rahasia.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php while ($row = mysqli_fetch_assoc($kandidat)): ?>
                <div class="bg-white rounded-xl shadow p-6 text-center border-t-4 border-slate-900">
                    <div class="text-4xl font-black text-slate-200 mb-2">0<?= $row['nomor_urut']; ?></div>
                    <h3 class="text-xl font-bold text-slate-800"><?= $row['nama_ketua']; ?></h3>
                    <h3 class="text-lg font-semibold text-slate-600 mb-4">& <?= $row['nama_wakil']; ?></h3>
                    <p class="text-slate-500 text-sm italic mb-6">"<?= htmlspecialchars($row['visi_misi']); ?>"</p>
                    
                    <form method="POST" action="proses_vote.php" onsubmit="return confirm('Apakah Anda yakin ingin memilih Paslon 0<?= $row['nomor_urut']; ?>? Pilihan tidak dapat diubah.');">
                        <input type="hidden" name="candidate_id" value="<?= $row['id']; ?>">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-yellow-500 hover:text-slate-900 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md">
                            COBLOS PASLON 0<?= $row['nomor_urut']; ?>
                        </button>
                    </form>
                </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
