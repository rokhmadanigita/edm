<?php
require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? '';
$body = input();

$pdo->exec("CREATE TABLE IF NOT EXISTS guestbook (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(120) NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    pesan TEXT NOT NULL,
    dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

switch ($action) {
    case 'list':
        requireAdmin();
        $stmt = $pdo->query("SELECT id, nama AS name, email, pesan AS message, DATE_FORMAT(dibuat_pada, '%d %M %Y %H:%i') AS date FROM guestbook ORDER BY id DESC LIMIT 40");
        respond(['rows' => $stmt->fetchAll()]);
        break;

    case 'add':
        $name = trim($body['name'] ?? '');
        $email = trim($body['email'] ?? '');
        $message = trim($body['message'] ?? '');

        if (!$name || !$message) {
            respond(['error' => 'Nama dan pesan wajib diisi.'], 400);
        }

        $stmt = $pdo->prepare('INSERT INTO guestbook (nama, email, pesan) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email ?: null, $message]);

        respond(['ok' => true]);
        break;

    case 'delete':
        requireAdmin();
        $id = (int) ($body['id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM guestbook WHERE id = ?');
        $stmt->execute([$id]);
        respond(['ok' => true]);
        break;

    default:
        respond(['error' => 'Aksi tidak dikenal.'], 400);
}
