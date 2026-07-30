<?php
require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? '';
$body = input();

switch ($action) {

  case 'login':
    $sandi = (string) ($body['password'] ?? '');
    if (!hash_equals(ADMIN_PASSWORD, $sandi)) {
      respond(['error' => 'Kata sandi salah.'], 401);
    }
    $_SESSION['is_admin'] = true;
    respond(['ok' => true]);
    break;

  case 'logout':
    unset($_SESSION['is_admin']);
    respond(['ok' => true]);
    break;

  case 'check':
    respond(['isAdmin' => !empty($_SESSION['is_admin'])]);
    break;

  case 'stats':
    requireAdmin();
    $totalSiswa   = (int) $pdo->query('SELECT COUNT(*) c FROM siswa')->fetch()['c'];
    $totalAbsensi = (int) $pdo->query('SELECT COUNT(*) c FROM absensi')->fetch()['c'];
    $totalBerita  = (int) $pdo->query('SELECT COUNT(*) c FROM berita')->fetch()['c'];
    $hadirHariIni = (int) $pdo->query("SELECT COUNT(DISTINCT siswa_id) c FROM absensi WHERE tanggal = CURDATE() AND jenis = 'masuk'")->fetch()['c'];
    respond([
      'totalStudents' => $totalSiswa,
      'totalAttendance' => $totalAbsensi,
      'totalNews' => $totalBerita,
      'todayIn' => $hadirHariIni,
    ]);
    break;

  case 'roster':
    requireAdmin();
    $stmt = $pdo->query('SELECT id, nama AS name, sekolah AS school, jurusan, kelas, username FROM siswa ORDER BY nama');
    respond(['rows' => $stmt->fetchAll()]);
    break;

  case 'roster_delete':
    requireAdmin();
    $id = (int) ($body['id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM siswa WHERE id = ?');
    $stmt->execute([$id]);
    respond(['ok' => true]);
    break;

  default:
    respond(['error' => 'Aksi tidak dikenal.'], 400);
}
