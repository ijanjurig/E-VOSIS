CREATE DATABASE IF NOT EXISTS evosis_smaper;
USE evosis_smaper;

-- Tabel Pengguna (Admin, Guru, Siswa)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    password_hash VARCHAR(255) NOT NULL, -- Menyimpan NISN/NIP yang sudah di-hash demi keamanan
    role ENUM('admin', 'panitia', 'pengawas', 'siswa', 'guru') NOT NULL,
    class VARCHAR(50) DEFAULT NULL,
    has_voted BOOLEAN DEFAULT FALSE
);

-- Tabel Pasangan Calon
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_urut INT NOT NULL,
    nama_ketua VARCHAR(100) NOT NULL,
    nama_wakil VARCHAR(100) NOT NULL,
    visi_misi TEXT NOT NULL
);

-- Tabel Surat Suara (ANONIM - Terpisah dari entitas users)
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidate_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE
);

-- ==========================================
-- DATA DUMMY UNTUK UJI COBA
-- Password di bawah adalah hash bcrypt dari "123456" (misal ini NISN/NIP-nya)
-- ==========================================

INSERT INTO users (name, password_hash, role, class, has_voted) VALUES 
('Admin Pusat', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 0),
('Bpk Ana Suryana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'guru', 'Pembina', 0),
('Alif Fatih', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa', 'XII-B', 0);

INSERT INTO candidates (nomor_urut, nama_ketua, nama_wakil, visi_misi) VALUES 
(1, 'Salka Rapilia', 'Siti Sipa', 'Mewujudkan OSIS yang Proaktif dan Kreatif.'),
(2, 'M. Ilham', 'Devika', 'OSIS Inovatif, Berbudaya, dan Berakhlak Mulia.');
