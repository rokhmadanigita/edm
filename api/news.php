<?php
require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? '';
$body = input();

switch ($action) {

  case 'list':
    $stmt = $pdo->query("SELECT id, judul AS title, kategori AS tag, isi AS body, foto AS photo, IFNULL(penulis, 'ED Management') AS author, DATE_FORMAT(dibuat_pada, '%d %M %Y') AS date FROM berita ORDER BY id DESC");
    respond(['rows' => $stmt->fetchAll()]);
    break;

  case 'detail':
    $id = (int) ($_GET['id'] ?? 0);
    $stmt = $pdo->prepare("SELECT id, judul AS title, kategori AS tag, isi AS body, foto AS photo, IFNULL(penulis, 'ED Management') AS author, DATE_FORMAT(dibuat_pada, '%d %M %Y') AS date FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) respond(['error' => 'Berita tidak ditemukan.'], 404);
    respond(['news' => $row]);
    break;

  case 'save':
    $studentId = !empty($_SESSION['student_id']) ? (int) $_SESSION['student_id'] : null;
    $author = !empty($_SESSION['student_name']) ? trim($_SESSION['student_name']) : null;
    $photo = isset($body['photo']) && is_string($body['photo']) ? trim($body['photo']) : '';

    if ($id = (int) ($body['id'] ?? 0)) {
      requireAdmin();
      $judul   = trim($body['title'] ?? '');
      $kategori = trim($body['tag'] ?? '');
      $isi     = trim($body['body'] ?? '');

      if (!$judul || !$isi) respond(['error' => 'Judul dan isi berita wajib diisi.'], 400);
      if ($photo && preg_match('/^data:image\/(\w+);base64,(.*)$/', $photo, $m)) {
        $ext = strtolower($m[1]) === 'png' ? 'png' : 'jpg';
        $photoFilename = 'news_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;
        $photoPath = __DIR__ . '/../assets/img/' . $photoFilename;
        $imageData = base64_decode($m[2], true);
        if ($imageData !== false) {
          file_put_contents($photoPath, $imageData);
          $stmt = $pdo->prepare('UPDATE berita SET judul = ?, kategori = ?, isi = ?, foto = ? WHERE id = ?');
          $stmt->execute([$judul, $kategori, $isi, $photoFilename, $id]);
        } else {
          respond(['error' => 'Format gambar tidak valid.'], 400);
        }
      } else {
        $stmt = $pdo->prepare('UPDATE berita SET judul = ?, kategori = ?, isi = ? WHERE id = ?');
        $stmt->execute([$judul, $kategori, $isi, $id]);
      }
    } else {
      $judul   = trim($body['title'] ?? '');
      $kategori = trim($body['tag'] ?? '');
      $isi     = trim($body['body'] ?? '');

      if (!$judul || !$isi) respond(['error' => 'Judul dan isi berita wajib diisi.'], 400);
      if (!$studentId) requireAdmin();

      $photoFilename = null;
      if ($photo && preg_match('/^data:image\/(\w+);base64,(.*)$/', $photo, $m)) {
        $ext = strtolower($m[1]) === 'png' ? 'png' : 'jpg';
        $photoFilename = 'news_' . time() . '_' . random_int(1000, 9999) . '.' . $ext;
        $photoPath = __DIR__ . '/../assets/img/' . $photoFilename;
        $imageData = base64_decode($m[2], true);
        if ($imageData !== false) {
          file_put_contents($photoPath, $imageData);
        } else {
          respond(['error' => 'Format gambar tidak valid.'], 400);
        }
      }

      $stmt = $pdo->prepare('INSERT INTO berita (judul, kategori, isi, penulis, siswa_id, foto) VALUES (?, ?, ?, ?, ?, ?)');
      $stmt->execute([$judul, $kategori, $isi, $author, $studentId, $photoFilename]);
    }
    respond(['ok' => true]);
    break;

  case 'delete':
    requireAdmin();
    $id = (int) ($body['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM berita WHERE id = ?');
    $stmt->execute([$id]);
    respond(['ok' => true]);
    break;

  default:
    respond(['error' => 'Aksi tidak dikenal.'], 400);
}
