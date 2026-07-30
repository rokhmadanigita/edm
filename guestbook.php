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

    <div class="guestbook-list">
      <div class="section-head" style="padding-bottom:0; margin-bottom:10px; border-bottom:none;">
        <h2>Pesan Terbaru</h2>
        <span class="num">40 entri terakhir</span>
      </div>
      <div class="guestbook-entries" id="guestbookEntries"></div>
      <p class="empty-note" id="guestbookEmpty" style="display:none;">Belum ada pesan. Jadilah yang pertama menyapa ED Management.</p>
    </div>
  </div>
</section>

<?php
$pageScript = <<<'JS'
async function loadGuestbook(){
  const entries = document.getElementById('guestbookEntries');
  const empty = document.getElementById('guestbookEmpty');
  try{
    const res = await api('api/guestbook.php?action=list');
    entries.innerHTML = res.rows.map(r => `
      <article class="guestbook-card">
        <div class="guestbook-card-meta">
          <div>
            <strong>${escapeHTML(r.name)}</strong>
            ${r.email ? `<a href="mailto:${escapeHTML(r.email)}">${escapeHTML(r.email)}</a>` : ''}
          </div>
          <span class="guestbook-card-date">${escapeHTML(r.date)}</span>
        </div>
        <p class="guestbook-card-message">${escapeHTML(r.message)}</p>
      </article>
    `).join('');
    empty.style.display = res.rows.length ? 'none' : 'block';
  }catch(e){
    entries.innerHTML = `<p class="empty-note">Gagal memuat daftar pesan: ${escapeHTML(e.message)}</p>`;
    empty.style.display = 'none';
  }
}

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
    loadGuestbook();
  }catch(e){
    msg.textContent = e.message;
    msg.className = 'msg show err';
  }
}

loadGuestbook();
JS;
require_once __DIR__ . '/partials/footer.php';
?>