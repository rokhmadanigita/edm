<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

session_name('edmgmt_session');
session_start();

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Koneksi database gagal. Periksa isian di api/config.php.']);
    exit;
}

// Ensure foto column exists in absensi table
try {
    $checkCol = $pdo->query("SHOW COLUMNS FROM absensi WHERE Field = 'foto'");
    if ($checkCol->rowCount() === 0) {
        $pdo->exec("ALTER TABLE absensi ADD COLUMN foto VARCHAR(255) NULL AFTER catatan");
    }
} catch (Exception $e) {
    error_log('Warning: Could not update absensi table: ' . $e->getMessage());
}

// Ensure author columns exist in berita table
try {
    $checkCol = $pdo->query("SHOW COLUMNS FROM berita WHERE Field = 'penulis'");
    if ($checkCol->rowCount() === 0) {
        $pdo->exec("ALTER TABLE berita ADD COLUMN penulis VARCHAR(120) NULL AFTER kategori");
    }
    $checkCol = $pdo->query("SHOW COLUMNS FROM berita WHERE Field = 'siswa_id'");
    if ($checkCol->rowCount() === 0) {
        $pdo->exec("ALTER TABLE berita ADD COLUMN siswa_id INT NULL AFTER penulis");
    }
    $checkCol = $pdo->query("SHOW COLUMNS FROM berita WHERE Field = 'foto'");
    if ($checkCol->rowCount() === 0) {
        $pdo->exec("ALTER TABLE berita ADD COLUMN foto VARCHAR(255) NULL AFTER siswa_id");
    }
} catch (Exception $e) {
    error_log('Warning: Could not update berita table: ' . $e->getMessage());
}

function input(): array {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
}

function respond($data, int $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

function requireStudent(): int {
    if (empty($_SESSION['student_id'])) {
        respond(['error' => 'Kamu belum masuk ke akun.'], 401);
    }
    return (int) $_SESSION['student_id'];
}

function requireAdmin() {
    if (empty($_SESSION['is_admin'])) {
        respond(['error' => 'Akses ditolak.'], 403);
    }
}
