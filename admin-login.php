<?php
$pageTitle = 'Masuk Tim Internal — ED Management';
$isLoggedIn = false;
$studentName = '';
$isAdminSession = false;
require_once __DIR__ . '/partials/header.php';
if ($isAdminSession) { header('Location: ' . edm_url('admin.php')); exit; }
?>

<div class="auth-shell">
  <div class="auth-card">
    <h2>Masuk Tim Internal</h2>
    <p class="sub">Halaman ini khusus untuk tim pengelola ED Management.</p>
    <label for="adminPass">Kata sandi</label>
    <div class="password-field">
      <input type="password" id="adminPass" placeholder="••••••••" onkeydown="if(event.key==='Enter')adminLogin()">
      <button type="button" class="password-toggle" aria-label="Tampilkan kata sandi" title="Tampilkan kata sandi"></button>
    </div>
    <button class="btn btn-gold btn-block" style="margin-top:16px;" onclick="adminLogin()">Masuk</button>
    <div class="msg" id="loginMsg"></div>
  </div>
</div>

<?php
$pageScript = <<<'JS'
setupPasswordToggles();

async function adminLogin(){
  const val = document.getElementById('adminPass').value;
  const msg = document.getElementById('loginMsg');
  try{
    await api('api/admin.php?action=login', 'POST', {password: val});
    window.location.href = edmUrl('admin.php');
  }catch(e){
    msg.textContent = e.message;
    msg.className = 'msg show err';
  }
}
JS;
require_once __DIR__ . '/partials/footer.php';
?>
