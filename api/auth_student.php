<?php
require_once __DIR__ . '/db.php';

$action = $_GET['action'] ?? '';
$body = input();

switch ($action) {

  case 'signup':
    $nama     = trim($body['name'] ?? '');
    $sekolah  = trim($body['school'] ?? '');
    $jurusan  = trim($body['jurusan'] ?? '');
    $kelas    = trim($body['kelas'] ?? '');
    $username = strtolower(trim($body['username'] ?? ''));
    $sandi    = (string) ($body['password'] ?? '');

    if (!$nama || !$sekolah || !$jurusan || !$kelas || !$username || !$sandi) {
      respond(['error' => 'Semua kolom wajib diisi.'], 400);
    }
    if (strlen($sandi) < 4) {
      respond(['error' => 'Kata sandi minimal 4 karakter.'], 400);
    }

    $stmt = $pdo->prepare('SELECT id FROM siswa WHERE username = ?');
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
      respond(['error' => 'Username sudah dipakai, coba yang lain.'], 409);
    }

    $hash = password_hash($sandi, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO siswa (nama, sekolah, jurusan, kelas, username, kata_sandi) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$nama, $sekolah, $jurusan, $kelas, $username, $hash]);

    $id = (int) $pdo->lastInsertId();
    $_SESSION['student_id'] = $id;
    $_SESSION['student_name'] = $nama;

    respond(['student' => [
      'id' => $id, 'name' => $nama, 'school' => $sekolah,
      'jurusan' => $jurusan, 'kelas' => $kelas, 'username' => $username,
    ]]);
    break;

  case 'login':
    $username = strtolower(trim($body['username'] ?? ''));
    $sandi    = (string) ($body['password'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM siswa WHERE username = ?');
    $stmt->execute([$username]);
    $s = $stmt->fetch();

    if (!$s || !password_verify($sandi, $s['kata_sandi'])) {
      respond(['error' => 'Username atau kata sandi salah.'], 401);
    }

    $_SESSION['student_id'] = (int) $s['id'];
    $_SESSION['student_name'] = $s['nama'];

    respond(['student' => [
      'id' => $s['id'], 'name' => $s['nama'], 'school' => $s['sekolah'],
      'jurusan' => $s['jurusan'], 'kelas' => $s['kelas'], 'username' => $s['username'],
    ]]);
    break;

  case 'logout':
    unset($_SESSION['student_id']);
    unset($_SESSION['student_name']);
    respond(['ok' => true]);
    break;

  case 'me':
    if (empty($_SESSION['student_id'])) {
      respond(['student' => null]);
    }
    $stmt = $pdo->prepare('SELECT id, nama, sekolah, jurusan, kelas, username FROM siswa WHERE id = ?');
    $stmt->execute([$_SESSION['student_id']]);
    $s = $stmt->fetch();
    respond(['student' => $s ? [
      'id' => $s['id'], 'name' => $s['nama'], 'school' => $s['sekolah'],
      'jurusan' => $s['jurusan'], 'kelas' => $s['kelas'], 'username' => $s['username'],
    ] : null]);
    break;

  default:
    respond(['error' => 'Aksi tidak dikenal.'], 400);
}
