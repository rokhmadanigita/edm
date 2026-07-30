<?php
require_once __DIR__ . '/session.php';
$pageTitle = $pageTitle ?? 'ED Management';
$activePage = $activePage ?? '';
$baseUrl = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
function edm_url($path = '') {
  global $baseUrl;
  $path = ltrim($path, '/');
  return ($baseUrl ? $baseUrl : '') . '/' . $path;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= edm_url('assets/css/style.css') ?>">
</head>
<body>

<!-- ============ NAV ============ -->
<header class="nav">
  <div class="nav-inner">
    <a href="<?= edm_url('index.php') ?>" class="brand">
      <img class="crown" src="<?= edm_url('assets/img/logo.png') ?>" alt="ED Management">
      <span class="brand-text">ED <span>MANAGEMENT</span></span>
    </a>
    <button class="hamb" onclick="document.querySelector('.nav-center').classList.toggle('open')">☰</button>
    <div class="nav-center" id="navlinks">
      <nav class="links">
        <a href="<?= edm_url('index.php') ?>" class="<?= $activePage === 'home' ? 'active' : '' ?>">Beranda</a>
        <a href="<?= edm_url('index.php') ?>#tentang">Tentang</a>
        <a href="<?= edm_url('index.php') ?>#layanan">Layanan</a>
        <a href="<?= edm_url('berita.php') ?>" class="<?= $activePage === 'berita' ? 'active' : '' ?>">Berita</a>
        <a href="<?= edm_url('guestbook.php') ?>" class="<?= $activePage === 'guestbook' ? 'active' : '' ?>">Buku Tamu</a>
        <a href="<?= edm_url('absen.php') ?>" class="<?= $activePage === 'absen' ? 'active' : '' ?>">Portal PKL</a>
      </nav>
    </div>
    <div class="nav-right">
      <?php if ($isLoggedIn): ?>
        <span class="who">Halo, <span><?= htmlspecialchars(explode(' ', $studentName)[0]) ?></span></span>
        <a href="<?= edm_url('absen.php') ?>" class="btn btn-gold btn-sm">Absen</a>
      <?php else: ?>
        <a href="<?= edm_url('masuk.php') ?>" class="btn btn-outline btn-sm">Masuk</a>
        <a href="<?= edm_url('daftar.php') ?>" class="btn btn-gold btn-sm">Daftar</a>
      <?php endif; ?>
    </div>
  </div>
</header>
