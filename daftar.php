<?php
$pageTitle = 'Daftar Akun — ED Management';
$activePage = 'absen';
$isLoggedIn = false;
$studentName = '';
$isAdminSession = false;
require_once __DIR__ . '/partials/header.php';
if ($isLoggedIn) { header('Location: ' . edm_url('absen.php')); exit; }
?>

<div class="auth-shell">
  <div class="auth-card">
    <h2>Daftar Akun Peserta</h2>
    <p class="sub">Isi data dirimu untuk mulai mencatat kehadiran PKL di ED Management.</p>
    <div class="form-grid">
      <div>
        <label for="suNama">Nama lengkap</label>
        <input type="text" id="suNama">
      </div>
      <div class="two-col">
        <div>
          <label for="suSekolah">Asal sekolah</label>
          <select id="suSekolah">
            <option value="">Pilih sekolah</option>
            <option value="SMK Negeri 1 Purwosari">SMK Negeri 1 Purwosari</option>
            <option value="SMK Negeri 1 Sukorejo">SMK Negeri 1 Sukorejo</option>
            <option value="SMK Negeri 2 Sukorejo">SMK Negeri 2 Sukorejo</option>
            <option value="SMK Negeri 1 Bangil">SMK Negeri 1 Bangil</option>
            <option value="SMK Negeri 1 Wonokerto">SMK Negeri 1 Wonokerto</option>
            <option value="Lainnya">Lainnya</option>
          </select>
        </div>
        <div>
          <label for="suKelas">Kelas</label>
          <select id="suKelas">
            <option value="">Pilih kelas</option>
            <option value="X">X</option>
            <option value="XI">XI</option>
            <option value="XII">XII</option>
          </select>
        </div>
      </div>
      <div>
        <label for="suJurusan">Jurusan</label>
        <select id="suJurusan">
          <option value="">Pilih jurusan</option>
          <option value="Broadcast">Broadcast</option>
          <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
          <option value="Desain Komunikasi Visual">Desain Komunikasi Visual</option>
        </select>
      </div>
      <div>
        <label for="suUsername">Username</label>
        <input type="text" id="suUsername" autocomplete="username">
      </div>
      <div class="two-col">
        <div>
          <label for="suPass">Kata sandi</label>
          <div class="password-field">
            <input type="password" id="suPass" autocomplete="new-password">
            <button type="button" class="password-toggle" aria-label="Tampilkan kata sandi" title="Tampilkan kata sandi"></button>
          </div>
        </div>
        <div>
          <label for="suPass2">Ulangi sandi</label>
          <div class="password-field">
            <input type="password" id="suPass2" autocomplete="new-password">
            <button type="button" class="password-toggle" aria-label="Tampilkan kata sandi" title="Tampilkan kata sandi"></button>
          </div>
        </div>
      </div>
      <button class="btn btn-gold btn-block" onclick="doSignup()">Buat Akun</button>
      <div class="msg" id="signupMsg"></div>
    </div>
    <p class="auth-switch">Sudah punya akun? <a href="<?= edm_url('masuk.php') ?>">Masuk di sini</a></p>
  </div>
</div>

<?php
$pageScript = <<<'JS'
setupPasswordToggles();

async function doSignup(){
  const name = document.getElementById('suNama').value.trim();
  const school = document.getElementById('suSekolah').value.trim();
  const jurusan = document.getElementById('suJurusan').value.trim();
  const kelas = document.getElementById('suKelas').value.trim();
  const username = document.getElementById('suUsername').value.trim().toLowerCase();
  const pass = document.getElementById('suPass').value;
  const pass2 = document.getElementById('suPass2').value;
  const msg = document.getElementById('signupMsg');
  msg.className = 'msg show err';
  if(!name || !school || !jurusan || !kelas || !username || !pass){ msg.textContent = 'Semua kolom wajib diisi.'; return; }
  if(pass.length < 4){ msg.textContent = 'Kata sandi minimal 4 karakter.'; return; }
  if(pass !== pass2){ msg.textContent = 'Konfirmasi kata sandi tidak cocok.'; return; }
  try{
    await api('api/auth_student.php?action=signup', 'POST', {name, school, jurusan, kelas, username, password: pass});
    window.location.href = edmUrl('absen.php');
  }catch(e){
    msg.textContent = e.message;
  }
}
JS;
require_once __DIR__ . '/partials/footer.php';
?>
