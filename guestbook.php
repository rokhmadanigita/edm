<?php
$pageTitle = 'Buku Tamu — ED Management';
$activePage = 'guestbook';
$isLoggedIn = false;
$studentName = '';
$isAdminSession = false;
require_once __DIR__ . '/partials/header.php';
?>

<section class="guestbook-shell">
  <div class="wrap guestbook-layout">
    <div>
      <div class="section-head">
        <h2>Buku Tamu</h2>
        <span class="num">Tinggalkan pesan untuk ED Management</span>
      </div>
      <div class="card">
        <div class="form-grid">
          <div>
            <label for="gbName">Nama</label>
            <input type="text" id="gbName" placeholder="Masukkan namamu">
          </div>
          <div>
            <label for="gbEmail">Email (opsional)</label>
            <input type="text" id="gbEmail" placeholder="Contoh: nama@alamat.com">
          </div>
          <div>
            <label for="gbMessage">Pesan</label>
            <textarea id="gbMessage" placeholder="Tinggalkan pesan, saran, atau salam untuk tim ED Management..."></textarea>
          </div>
          <button class="btn btn-gold btn-block" onclick="submitGuestbook()">Kirim Pesan</button>
          <div class="msg" id="guestbookMsg"></div>
        </div>
      </div>
    </div>

  </div>
</section>

<?php
$pageScript = <<<'JS'
async function submitGuestbook(){
  const name = document.getElementById('gbName').value.trim();
  const email = document.getElementById('gbEmail').value.trim();
  const message = document.getElementById('gbMessage').value.trim();
  const msg = document.getElementById('guestbookMsg');

  try{
    if(!name || !message) throw new Error('Nama dan pesan wajib diisi.');
    await api('api/guestbook.php?action=add', 'POST', {name, email, message});
    msg.textContent = 'Pesan berhasil dikirim. Terima kasih!';
    msg.className = 'msg show ok';
    document.getElementById('gbName').value = '';
    document.getElementById('gbEmail').value = '';
    document.getElementById('gbMessage').value = '';
  }catch(e){
    msg.textContent = e.message;
    msg.className = 'msg show err';
  }
}
JS;
require_once __DIR__ . '/partials/footer.php';
?>