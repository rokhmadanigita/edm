<?php
$pageTitle = 'Absen PKL — ED Management';
$activePage = 'absen';
$isLoggedIn = false;
$studentName = '';
$isAdminSession = false;
require_once __DIR__ . '/partials/header.php';
?>

<section class="absen-shell">
  <div class="wrap">
    <div class="absen-heading">
      <div>
        <div class="eyebrow">Form kehadiran</div>
        <h1>Absen PKL</h1>
        <p class="lede">Kehadiran dicatat langsung ke akunmu yang sedang login. Gunakan tombol Masuk saat tiba dan Pulang saat pulang kerja.</p>
      </div>
      <div class="absen-clock">
        <span class="clock-label">Waktu real-time</span>
        <div class="clock-time" id="clockTime">00:00:00</div>
        <div class="clock-date" id="clockDate">Memuat tanggal…</div>
      </div>
    </div>

    <?php if (!$isLoggedIn): ?>
      <div class="auth-shell" style="background:transparent; box-shadow:none;">
        <div class="auth-card">
          <h2>Masuk dulu untuk absen</h2>
          <p class="sub">Akses portal PKL untuk siswa hanya bisa setelah masuk.</p>
          <div class="cta-row" style="justify-content:center; gap:14px; margin-top:22px;">
            <a href="<?= edm_url('masuk.php') ?>" class="btn btn-dark">Masuk</a>
            <a href="<?= edm_url('daftar.php') ?>" class="btn btn-outline">Daftar Akun</a>
          </div>
        </div>
      </div>

    <?php else: ?>
      <div class="profile-strip" style="margin-top:24px;">
        <div>
          <div class="pname" id="absenProfileName">Memuat…</div>
          <div class="pmeta" id="absenProfileMeta"></div>
        </div>
        <button class="icon-btn" onclick="doLogout()">Keluar</button>
      </div>

      <div class="absen-card-grid">
        <div class="card absen-panel">
          <div class="form-grid">
            <div>
              <label>Status</label>
              <div class="type-toggle">
                <button type="button" id="btnMasuk" class="active" onclick="setAbsenType('masuk')">Masuk</button>
                <button type="button" id="btnPulang" onclick="setAbsenType('pulang')">Pulang</button>
              </div>
            </div>
            <div>
              <label for="absenCatatan">Catatan (opsional)</label>
              <textarea id="absenCatatan" placeholder="cth. izin datang telat karena hujan"></textarea>
            </div>
            <div>
              <label for="absenPhoto">Foto masuk dari kamera</label>
              <input id="absenPhoto" type="file" accept="image/*" capture="environment" />
              <small class="helptext">Untuk absensi masuk, foto diambil langsung dari kamera HP supaya tidak perlu memilih dari galeri.</small>
            </div>
            <button class="btn btn-dark btn-block" onclick="submitAbsen()">Catat Kehadiran</button>
            <div class="msg" id="absenMsg"></div>
          </div>
        </div>

        <div class="card absen-panel" style="background:var(--black-2); color:var(--ivory); border-color:var(--line);">
          <div style="display:flex; flex-direction:column; gap:16px;">
            <div>
              <span class="label" style="color:var(--ivory);">Petunjuk singkat</span>
              <ul class="program-list" style="color:var(--ivory);">
                <li><b>⏱️</b> Pastikan jam saat ini sesuai sebelum tekan absen.</li>
                <li><b>→</b> Pilih Masuk saat tiba dan Pulang saat selesai PKL.</li>
                <li><b>•</b> Catat alasan telat atau izin di kolom catatan.</li>
              </ul>
            </div>
            <div>
              <span class="label" style="color:var(--ivory);">Status terakhir</span>
              <div class="status-chip" id="lastAttendanceStatus">Memuat…</div>
            </div>
          </div>
        </div>
      </div>

      <div class="absen-panel" style="margin-top:32px; padding:28px;">
        <div class="section-head" style="margin-bottom:16px; padding-bottom:0; border-bottom:none;">
          <h2 style="font-size:19px;">Rekap kehadiran hari ini</h2>
          <span class="num mono" id="todayDate"></span>
        </div>
        <div style="overflow-x:auto;">
          <table class="log-table">
            <thead><tr><th>Status</th><th>Jam</th><th>Catatan</th></tr></thead>
            <tbody id="todayLogBody"></tbody>
          </table>
        </div>
        <p class="empty-note" id="todayEmpty" style="display:none; padding:18px 0;">— kamu belum absen hari ini —</p>
      </div>

      <div class="rankmeter" style="margin-top:32px;">
        <div class="chevstack" id="rankChevStack"></div>
        <div>
          <div class="label">Pangkat kehadiranmu</div>
          <div class="title" id="rankTitle">Memuat…</div>
          <div class="desc" id="rankDesc"></div>
        </div>
      </div>

      <div class="card absen-panel" style="margin-top:32px;">
        <div class="section-head" style="margin-bottom:16px; padding-bottom:0; border-bottom:none;">
          <h2 style="font-size:19px;">Buat Berita</h2>
          <span class="num mono">Bagikan informasi atau kegiatan PKL</span>
        </div>
        <div class="form-grid">
          <div>
            <label for="newsTitleInput">Judul berita</label>
            <input type="text" id="newsTitleInput" placeholder="Judul singkat untuk berita">
          </div>
          <div>
            <label for="newsTagInput">Kategori</label>
            <input type="text" id="newsTagInput" placeholder="Contoh: Kegiatan, Pengumuman">
          </div>
          <div>
            <label for="newsPhotoInput">Foto berita (opsional)</label>
            <input type="file" id="newsPhotoInput" accept="image/*">
          </div>
          <div>
            <label for="newsBodyInput">Isi berita</label>
            <textarea id="newsBodyInput" placeholder="Tuliskan detail berita di sini..."></textarea>
          </div>
          <button class="btn btn-dark btn-block" onclick="submitNews()">Kirim Berita</button>
          <div class="msg" id="newsMsg"></div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php
$pageScript = <<<'JS'
let absenType = 'masuk';

function setAbsenType(t){
  absenType = t;
  document.getElementById('btnMasuk').classList.toggle('active', t==='masuk');
  document.getElementById('btnPulang').classList.toggle('active', t==='pulang');
}

async function doLogout(){
  try{ await api('api/auth_student.php?action=logout', 'POST'); }catch(e){}
  window.location.href = edmUrl('index.php');
}

function updateClock(){
  const timeEl = document.getElementById('clockTime');
  const dateEl = document.getElementById('clockDate');
  if(!timeEl || !dateEl) return;
  const now = new Date();
  timeEl.textContent = now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
  dateEl.textContent = now.toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'});
}

function readPhotoData(file){
  return new Promise((resolve, reject) => {
    if (!file) return resolve('');
    const reader = new FileReader();
    reader.onload = () => resolve(String(reader.result || ''));
    reader.onerror = () => reject(new Error('Gagal membaca foto dari kamera.'));
    reader.readAsDataURL(file);
  });
}

async function submitAbsen(){
  const note = document.getElementById('absenCatatan').value.trim();
  const photoInput = document.getElementById('absenPhoto');
  const msg = document.getElementById('absenMsg');
  try{
    const photo = await readPhotoData(photoInput && photoInput.files ? photoInput.files[0] : null);
    if (absenType === 'masuk' && !photo) {
      throw new Error('Ambil foto dari kamera dulu sebelum absen masuk.');
    }

    await api('api/attendance.php?action=add', 'POST', {type: absenType, note, photo});
    msg.textContent = `Berhasil dicatat: ${absenType.toUpperCase()} pukul ${new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit'})}.`;
    msg.className = 'msg show ok';
    document.getElementById('absenCatatan').value = '';
    if (photoInput) photoInput.value = '';
    renderTodayLog();
    renderRank();
  }catch(e){
    msg.textContent = e.message;
    msg.className = 'msg show err';
  }
}

function updateLastAttendanceStatus(rows){
  const statusEl = document.getElementById('lastAttendanceStatus');
  if(!statusEl) return;
  if(!rows.length){
    statusEl.textContent = 'Belum absen hari ini';
    statusEl.className = 'status-chip';
    return;
  }
  const last = rows[rows.length - 1];
  statusEl.textContent = `${last.type.toUpperCase()} · ${last.time}`;
  statusEl.className = `status-chip ${last.type}`;
}

async function renderTodayLog(){
  const body = document.getElementById('todayLogBody');
  const empty = document.getElementById('todayEmpty');
  let rows = [];
  try{
    const res = await api('api/attendance.php?action=today');
    rows = res.rows;
  }catch(e){}
  body.innerHTML = rows.map(a => `
    <tr>
      <td><span class="badge ${a.type}">${a.type}</span></td>
      <td class="mono">${escapeHTML(a.time)}</td>
      <td>${escapeHTML(a.note||'—')}</td>
    </tr>`).join('');
  empty.style.display = rows.length ? 'none' : 'block';
  updateLastAttendanceStatus(rows);
}

async function renderRank(){
  let count = 0;
  try{
    const res = await api('api/attendance.php?action=mine');
    count = new Set(res.dates).size;
  }catch(e){}
  const rank = computeRank(count);
  renderChevronRank('rankChevStack', rank.level, 4);
  document.getElementById('rankTitle').textContent = rank.title;
  document.getElementById('rankDesc').textContent = rank.desc;
}

async function readFileAsBase64(file){
  return new Promise((resolve, reject) => {
    if (!file) return resolve('');
    const reader = new FileReader();
    reader.onload = () => resolve(String(reader.result || ''));
    reader.onerror = () => reject(new Error('Gagal membaca file gambar.'));
    reader.readAsDataURL(file);
  });
}

async function submitNews(){
  const title = document.getElementById('newsTitleInput').value.trim();
  const tag = document.getElementById('newsTagInput').value.trim();
  const body = document.getElementById('newsBodyInput').value.trim();
  const photoInput = document.getElementById('newsPhotoInput');
  const msg = document.getElementById('newsMsg');
  try{
    if(!title || !body) throw new Error('Judul dan isi berita wajib diisi.');
    const photo = await readFileAsBase64(photoInput && photoInput.files ? photoInput.files[0] : null);
    await api('api/news.php?action=save', 'POST', {title, tag, body, photo});
    msg.textContent = 'Berita berhasil dikirim.';
    msg.className = 'msg show ok';
    document.getElementById('newsTitleInput').value = '';
    document.getElementById('newsTagInput').value = '';
    document.getElementById('newsBodyInput').value = '';
    if (photoInput) photoInput.value = '';
  }catch(e){
    msg.textContent = e.message;
    msg.className = 'msg show err';
  }
}

async function initAbsenPage(){
  updateClock();
  setInterval(updateClock, 1000);
  try{
    const me = await api('api/auth_student.php?action=me');
    if(!me.student){ window.location.href = edmUrl('masuk.php'); return; }
    document.getElementById('absenProfileName').textContent = me.student.name;
    document.getElementById('absenProfileMeta').textContent = `${me.student.school} · ${me.student.jurusan} · ${me.student.kelas}`;
  }catch(e){}
  document.getElementById('todayDate').textContent = todayStr();
  renderTodayLog();
  renderRank();
}
initAbsenPage();
JS;
require_once __DIR__ . '/partials/footer.php';
?>
