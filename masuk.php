<?php
$pageTitle = 'Masuk — ED Management';
$activePage = 'absen';
$isLoggedIn = false;
$studentName = '';
$isAdminSession = false;
require_once __DIR__ . '/partials/header.php';
if ($isLoggedIn) { header('Location: ' . edm_url('absen.php')); exit; }
?>

<div class="auth-shell">
  <div class="auth-card">
    <h2>Masuk</h2>
    <p class="sub">Masukkan username dan kata sandi akunmu.</p>
    <div class="form-grid">
      <div>
        <label for="liUsername">Username</label>
        <input type="text" id="liUsername" autocomplete="username" onkeydown="if(event.key==='Enter')doLogin()">
      </div>
      <div>
        <label for="liPass">Kata sandi</label>
        <div class="password-field">
          <input type="password" id="liPass" autocomplete="current-password" onkeydown="if(event.key==='Enter')doLogin()">
          <button type="button" class="password-toggle" aria-label="Tampilkan kata sandi" title="Tampilkan kata sandi"></button>
        </div>
      </div>
      <button class="btn btn-gold btn-block" onclick="doLogin()">Masuk</button>
      <div class="msg" id="loginMsgStudent"></div>
    </div>
    <p class="auth-switch">Belum punya akun? <a href="<?= edm_url('daftar.php') ?>">Daftar di sini</a></p>
  </div>
</div>

<?php
$pageScript = <<<'JS'
setupPasswordToggles();

async function doLogin(){
  const username = document.getElementById('liUsername').value.trim().toLowerCase();
  const pass = document.getElementById('liPass').value;
  const msg = document.getElementById('loginMsgStudent');
  try{
    await api('api/auth_student.php?action=login', 'POST', {username, password: pass});
    window.location.href = edmUrl('absen.php');
  }catch(e){
    msg.textContent = e.message;
    msg.className = 'msg show err';
  }
}
JS;
require_once __DIR__ . '/partials/footer.php';
?>
