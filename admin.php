Okay<?php
$pageTitle = 'Panel Admin — ED Management';
$isLoggedIn = false;
$studentName = '';
$isAdminSession = false;
require_once __DIR__ . '/partials/header.php';
if (!$isAdminSession) { header('Location: ' . edm_url('admin-login.php')); exit; }
?>

<div class="admin-shell">
  <div class="admin-side">
    <button data-t="rekap" class="active" onclick="setAdminTab('rekap')">Rekap Absensi</button>
    <button data-t="berita" onclick="setAdminTab('berita')">Kelola Berita</button>
    <button data-t="siswa" onclick="setAdminTab('siswa')">Daftar Peserta</button>
    <button data-t="guestbook" onclick="setAdminTab('guestbook')">Buku Tamu</button>
    <button onclick="adminLogout()" style="margin-top:20px; color:var(--warn);">Keluar</button>
  </div>
  <div class="admin-main">

    <div class="admin-tab active" id="tab-rekap">
      <div class="stat-row" id="statRow"></div>
      <div class="filter-row">
        <input type="text" id="filterNama" placeholder="cari nama…" oninput="renderRekap()">
        <input type="date" id="filterTanggal" oninput="renderRekap()">
        <select id="filterStatus" onchange="renderRekap()">
          <option value="">Semua status</option>
          <option value="masuk">Masuk</option>
          <option value="izin">Izin</option>
          <option value="sakit">Sakit</option>
        </select>
        <button class="icon-btn" onclick="clearFilters()">Reset filter</button>
      </div>
      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead><tr><th>Tanggal</th><th>Jam</th><th>Nama</th><th>Sekolah</th><th>Kelas</th><th>Status</th><th>Catatan</th><th>Foto</th><th></th></tr></thead>
          <tbody id="rekapBody"></tbody>
        </table>
      </div>
    </div>

    <div class="admin-tab" id="tab-berita">
      <div class="section-head" style="border:none; margin-bottom:12px; padding-bottom:0;">
        <h2 style="font-size:22px;">Kelola Berita</h2>
        <button class="btn btn-dark btn-sm" onclick="openNewsModal()">+ Tulis Berita</button>
      </div>
      <div class="news-admin-list" id="newsAdminList"></div>
    </div>

    <div class="admin-tab" id="tab-siswa">
      <div class="section-head" style="border:none; margin-bottom:12px; padding-bottom:0;">
        <h2 style="font-size:22px;">Daftar Peserta</h2>
        <span class="helptext">Peserta mendaftar sendiri lewat halaman Daftar Akun.</span>
      </div>
      <div class="roster-list" id="rosterList"></div>
    </div>

    <div class="admin-tab" id="tab-guestbook">
      <div class="section-head" style="border:none; margin-bottom:12px; padding-bottom:0;">
        <h2 style="font-size:22px;">Pesan Buku Tamu</h2>
        <span class="helptext">Pesan yang dikirim dari halaman Buku Tamu akan muncul di sini.</span>
      </div>
      <div class="guestbook-admin-list" id="guestbookAdminList"></div>
    </div>

  </div>
</div>

<div class="modal-bg" id="newsModalBg">
  <div class="modal">
    <h3 id="newsModalTitle">Tulis Berita</h3>
    <div class="form-grid">
      <input type="hidden" id="newsId">
      <div>
        <label for="newsJudul">Judul</label>
        <input type="text" id="newsJudul">
      </div>
      <div>
        <label for="newsTag">Kategori</label>
        <input type="text" id="newsTag" placeholder="cth. Pengumuman, Kegiatan">
      </div>
      <div>
        <label for="newsIsi">Isi berita</label>
        <textarea id="newsIsi" style="min-height:160px;"></textarea>
      </div>
      <div style="display:flex; gap:10px;">
        <button class="btn btn-outline" style="flex:1; color:#333; border-color:#d8d5c8;" onclick="closeModal('newsModalBg')">Batal</button>
        <button class="btn btn-dark" style="flex:1;" onclick="saveNews()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<?php
$pageScript = <<<'JS'
let newsCache = [];

function setAdminTab(tab){
  document.querySelectorAll('.admin-side button[data-t]').forEach(b => b.classList.toggle('active', b.dataset.t === tab));
  document.querySelectorAll('.admin-tab').forEach(t => t.classList.toggle('active', t.id === 'tab-'+tab));
}

async function adminLogout(){
  try{ await api('api/admin.php?action=logout', 'POST'); }catch(e){}
  window.location.href = edmUrl('index.php');
}

async function renderStats(){
  const row = document.getElementById('statRow');
  try{
    const s = await api('api/admin.php?action=stats');
    row.innerHTML = `
      <div class="stat"><div class="n">${s.totalStudents}</div><div class="l">Total peserta</div></div>
      <div class="stat"><div class="n">${s.todayIn}</div><div class="l">Hadir hari ini</div></div>
      <div class="stat"><div class="n">${s.totalAttendance}</div><div class="l">Total catatan</div></div>
      <div class="stat"><div class="n">${s.totalNews}</div><div class="l">Berita terbit</div></div>
    `;
  }catch(e){
    row.innerHTML = `<p class="empty-note">Gagal memuat statistik: ${escapeHTML(e.message)}</p>`;
  }
}

function clearFilters(){
  document.getElementById('filterNama').value = '';
  document.getElementById('filterTanggal').value = '';
  document.getElementById('filterStatus').value = '';
  renderRekap();
}

async function renderRekap(){
  const name = document.getElementById('filterNama').value.trim();
  const date = document.getElementById('filterTanggal').value;
  const status = document.getElementById('filterStatus').value;
  const params = new URLSearchParams({action:'list', name, date, status});
  const body = document.getElementById('rekapBody');
  let rows = [];
  try{
    const res = await api('api/attendance.php?' + params.toString());
    rows = res.rows;
  }catch(e){
    body.innerHTML = `<tr><td colspan="9" class="empty-note" style="padding:20px;">Gagal memuat: ${escapeHTML(e.message)}</td></tr>`;
    return;
  }
  body.innerHTML = rows.map(a => `
    <tr>
      <td class="mono">${escapeHTML(a.att_date)}</td>
      <td class="mono">${escapeHTML(a.att_time)}</td>
      <td>${escapeHTML(a.student_name)}</td>
      <td>${escapeHTML(a.school||'—')}</td>
      <td>${escapeHTML(a.kelas||'—')}</td>
      <td><span class="badge ${a.type}">${a.type}</span></td>
      <td>${escapeHTML(a.note||'—')}</td>
      <td>${a.photo ? `<a href="assets/img/${escapeHTML(a.photo)}" target="_blank" class="photo-link" title="Lihat foto">📷</a>` : '—'}</td>
      <td><button class="icon-btn danger" onclick="deleteAttendance(${a.id})">Hapus</button></td>
    </tr>`).join('') || `<tr><td colspan="9" class="empty-note" style="padding:20px;">— tidak ada data —</td></tr>`;
}
async function deleteAttendance(id){
  if(!confirm('Hapus catatan absensi ini?')) return;
  try{
    await api('api/attendance.php?action=delete', 'POST', {id});
    renderStats(); renderRekap();
  }catch(e){ alert(e.message); }
}

async function renderNewsAdmin(){
  const list = document.getElementById('newsAdminList');
  try{
    const res = await api('api/news.php?action=list');
    newsCache = res.rows;
  }catch(e){
    list.innerHTML = `<p class="empty-note">Gagal memuat berita: ${escapeHTML(e.message)}</p>`;
    return;
  }
  list.innerHTML = newsCache.map(n => `
    <div class="news-admin-item">
      <div>
        <span class="news-tag" style="color:#c6a15b;">${escapeHTML(n.tag||'Berita')} · ${escapeHTML(n.date)}</span>
        <h4>${escapeHTML(n.title)}</h4>
        <p>${escapeHTML((n.body||'').slice(0,120))}${(n.body||'').length>120?'…':''}</p>
      </div>
      <div style="display:flex; gap:8px; flex-shrink:0;">
        <button class="icon-btn" onclick="openNewsModal(${n.id})">Edit</button>
        <button class="icon-btn danger" onclick="deleteNews(${n.id})">Hapus</button>
      </div>
    </div>`).join('') || '<p class="empty-note">— belum ada berita —</p>';
}
function openNewsModal(id){
  document.getElementById('newsId').value = id || '';
  if(id){
    const n = newsCache.find(x => x.id === id);
    document.getElementById('newsModalTitle').textContent = 'Edit Berita';
    document.getElementById('newsJudul').value = n.title;
    document.getElementById('newsTag').value = n.tag || '';
    document.getElementById('newsIsi').value = n.body;
  } else {
    document.getElementById('newsModalTitle').textContent = 'Tulis Berita';
    document.getElementById('newsJudul').value = '';
    document.getElementById('newsTag').value = '';
    document.getElementById('newsIsi').value = '';
  }
  document.getElementById('newsModalBg').classList.add('show');
}
async function saveNews(){
  const id = document.getElementById('newsId').value;
  const title = document.getElementById('newsJudul').value.trim();
  const tag = document.getElementById('newsTag').value.trim();
  const body = document.getElementById('newsIsi').value.trim();
  if(!title || !body){ alert('Judul dan isi berita wajib diisi.'); return; }
  try{
    await api('api/news.php?action=save', 'POST', {id: id || 0, title, tag, body});
    closeModal('newsModalBg');
    renderNewsAdmin(); renderStats();
  }catch(e){ alert(e.message); }
}
async function deleteNews(id){
  if(!confirm('Hapus berita ini?')) return;
  try{
    await api('api/news.php?action=delete', 'POST', {id});
    renderNewsAdmin(); renderStats();
  }catch(e){ alert(e.message); }
}

async function renderRoster(){
  const list = document.getElementById('rosterList');
  let rows = [];
  try{
    const res = await api('api/admin.php?action=roster');
    rows = res.rows;
  }catch(e){
    list.innerHTML = `<p class="empty-note">Gagal memuat peserta: ${escapeHTML(e.message)}</p>`;
    return;
  }
  list.innerHTML = rows.map(s => `
    <div class="roster-item">
      <div>
        <strong>${escapeHTML(s.name)}</strong>
        <span class="rmeta">@${escapeHTML(s.username)} · ${escapeHTML(s.school)} · ${escapeHTML(s.jurusan)} · ${escapeHTML(s.kelas)}</span>
      </div>
      <button class="icon-btn danger" onclick="deleteStudent(${s.id})">Hapus</button>
    </div>`).join('') || '<p class="empty-note">— belum ada peserta terdaftar —</p>';
}
async function deleteStudent(id){
  if(!confirm('Hapus peserta ini beserta akunnya?')) return;
  try{
    await api('api/admin.php?action=roster_delete', 'POST', {id});
    renderRoster(); renderStats();
  }catch(e){ alert(e.message); }
}

async function renderGuestbookAdmin(){
  const list = document.getElementById('guestbookAdminList');
  try{
    const res = await api('api/guestbook.php?action=list');
    list.innerHTML = res.rows.map(g => `
      <div class="news-admin-item">
        <div>
          <span class="news-tag" style="color:#c6a15b;">${escapeHTML(g.date)}</span>
          <h4>${escapeHTML(g.name)}</h4>
          ${g.email ? `<p><strong>Email:</strong> ${escapeHTML(g.email)}</p>` : ''}
          <p>${escapeHTML(g.message)}</p>
        </div>
        <div style="display:flex; gap:8px; flex-shrink:0;">
          <button class="icon-btn danger" onclick="deleteGuestbook(${g.id})">Hapus</button>
        </div>
      </div>`).join('') || '<p class="empty-note">— belum ada pesan buku tamu —</p>';
  }catch(e){
    list.innerHTML = `<p class="empty-note">Gagal memuat pesan buku tamu: ${escapeHTML(e.message)}</p>`;
  }
}
async function deleteGuestbook(id){
  if(!confirm('Hapus pesan buku tamu ini?')) return;
  try{
    await api('api/guestbook.php?action=delete', 'POST', {id});
    renderGuestbookAdmin();
  }catch(e){ alert(e.message); }
}

function closeModal(id){ document.getElementById(id).classList.remove('show'); }
document.querySelectorAll('.modal-bg').forEach(bg => {
  bg.addEventListener('click', e => { if(e.target === bg) bg.classList.remove('show'); });
});

renderStats();
renderRekap();
renderNewsAdmin();
renderRoster();
renderGuestbookAdmin();
JS;
require_once __DIR__ . '/partials/footer.php';
?>
