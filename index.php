<?php
$pageTitle = 'ED Management — Pendampingan UMKM & Mitra PKL';
$activePage = 'home';
$isLoggedIn = false;
$studentName = '';
$isAdminSession = false;
require_once __DIR__ . '/partials/header.php';
?>

<section class="hero">
  <div class="wrap">
    <div>
      <div class="eyebrow">Perusahaan Pendamping UMKM & Mitra PKL</div>
      <h1>Membangun usaha,<br>mencetak <em>talenta baru.</em></h1>
      <p class="lede">ED Management adalah perusahaan yang bergerak di bidang pendampingan digitalisasi UMKM dan pengembangan sumber daya manusia — sekaligus membuka pintu bagi siswa/siswi untuk Praktik Kerja Lapangan (PKL) di tempat kami.</p>
      <div class="cta-row">
        <a href="#layanan" class="btn btn-gold">Lihat Layanan Kami →</a>
        <a href="<?= edm_url('absen.php') ?>" class="btn btn-outline">Portal PKL</a>
      </div>
    </div>
    <div>
      <img class="crown-hero" src="<?= edm_url('assets/img/logo.png') ?>" alt="ED Management">
    </div>
  </div>
</section>

<section id="tentang">
  <div class="wrap">
    <div class="section-head">
      <h2>Tentang ED Management</h2>
      <span class="num">profil singkat</span>
    </div>
    <p class="lede" style="color:#54524a; max-width:720px; margin-bottom:20px;">ED Management hadir sebagai mitra pertumbuhan pelaku usaha sekaligus tempat belajar bagi generasi muda. Kami mendampingi UMKM di Kabupaten Pasuruan agar naik kelas lewat digitalisasi, sambil membuka kesempatan Praktik Kerja Lapangan bagi siswa/siswi SMK dan sekolah mitra untuk belajar langsung di lingkungan kerja nyata.</p>
    <p class="lede" style="color:#54524a; max-width:720px; margin-bottom:36px;">Kami percaya pertumbuhan usaha dan pengembangan talenta muda berjalan beriringan — UMKM yang naik kelas butuh sumber daya manusia yang siap kerja, dan siswa PKL butuh lingkungan kerja nyata untuk belajar. Di situlah ED Management mengambil peran.</p>

    <div class="stat-row" style="margin-bottom:40px;">
      <div class="stat"><div class="n small">Pendampingan UMKM</div><div class="l">Mitra resmi KOMDIGI</div></div>
      <div class="stat"><div class="n small">Praktik Kerja Lapangan</div><div class="l">Terbuka untuk siswa/i</div></div>
      <div class="stat"><div class="n small">Kab. Pasuruan</div><div class="l">Wilayah kerja utama</div></div>
      <div class="stat"><div class="n small">Cendono, Purwosari</div><div class="l">Lokasi kantor</div></div>
    </div>

    <div class="about-grid">
      <div class="about-card">
        <span class="rank">Prinsip 01</span>
        <h3>Digitalisasi untuk semua</h3>
        <p>Usaha kecil sekalipun berhak punya identitas digital yang profesional dan mudah ditemukan.</p>
      </div>
      <div class="about-card">
        <span class="rank">Prinsip 02</span>
        <h3>Belajar dari praktik langsung</h3>
        <p>Siswa PKL belajar paling baik dengan terjun langsung, didampingi, bukan sekadar teori.</p>
      </div>
      <div class="about-card">
        <span class="rank">Prinsip 03</span>
        <h3>Pertumbuhan berkelanjutan</h3>
        <p>Baik UMKM maupun talenta muda, kami dampingi untuk berkembang secara bertahap dan berkelanjutan.</p>
      </div>
    </div>
  </div>
</section>

<section class="dark" id="layanan" style="border-top:1px solid var(--line);">
  <div class="wrap">
    <div class="section-head">
      <h2>Layanan Kami</h2>
      <span class="num mono">apa yang kami kerjakan</span>
    </div>
    <div class="program-grid2">
      <div class="program-block">
        <span class="rank" style="display:block; margin-bottom:10px;">Layanan 01</span>
        <h3 style="font-size:19px; margin-bottom:10px;">Pendampingan UMKM</h3>
        <p style="color:var(--steel); font-size:14px; margin-bottom:18px;">Program gratis bersama KOMDIGI untuk membantu UMKM di Kabupaten Pasuruan berkembang lebih profesional dan digital — mulai dari media sosial, microsite, hingga pemanfaatan AI untuk visual usaha.</p>
        <a href="#program" style="color:var(--gold-bright); font-family:'JetBrains Mono'; font-size:12.5px; text-decoration:underline;">Lihat detail program ↓</a>
      </div>
      <div class="program-block">
        <span class="rank" style="display:block; margin-bottom:10px;">Layanan 02</span>
        <h3 style="font-size:19px; margin-bottom:10px;">Praktik Kerja Lapangan (PKL)</h3>
        <p style="color:var(--steel); font-size:14px; margin-bottom:18px;">Kami menerima siswa/siswi PKL dari berbagai sekolah untuk belajar langsung di lingkungan kerja, lengkap dengan portal digital untuk pendaftaran akun dan pencatatan kehadiran harian.</p>
        <a href="<?= edm_url('absen.php') ?>" style="color:var(--gold-bright); font-family:'JetBrains Mono'; font-size:12.5px; text-decoration:underline;">Buka portal PKL →</a>
      </div>
    </div>
  </div>
</section>

<section class="dark" id="portal">
  <div class="wrap">
    <div class="section-head">
      <h2>Portal PKL</h2>
      <span class="num mono">fitur untuk siswa/i PKL</span>
    </div>
    <p class="lede" style="max-width:660px; margin-bottom:36px;">Salah satu layanan kami: siswa/siswi yang menjalani PKL di ED Management dapat mendaftar akun sendiri dan mencatat kehadiran harian lewat portal ini.</p>

    <div class="about-grid" style="background:var(--line); border-color:var(--line); margin-bottom:40px;">
      <div class="about-card" style="background:var(--black-2); color:var(--ivory);">
        <span class="rank">Langkah 01</span>
        <h3>Buat akun</h3>
        <p style="color:var(--steel);">Daftar sendiri dengan nama, asal sekolah, jurusan, dan kelas. Tidak perlu menunggu admin mendaftarkan.</p>
      </div>
      <div class="about-card" style="background:var(--black-2); color:var(--ivory);">
        <span class="rank">Langkah 02</span>
        <h3>Masuk & tandai hadir</h3>
        <p style="color:var(--steel);">Login dengan akunmu, lalu satu ketukan untuk mencatat jam masuk dan jam pulang setiap hari.</p>
      </div>
      <div class="about-card" style="background:var(--black-2); color:var(--ivory);">
        <span class="rank">Langkah 03</span>
        <h3>Terlihat oleh pembimbing</h3>
        <p style="color:var(--steel);">Rekap otomatis tersimpan dan bisa dipantau tim ED Management kapan saja.</p>
      </div>
    </div>

    <div class="rankmeter" style="margin-bottom:32px;">
      <div class="chevstack" id="homeChevStack"></div>
      <div>
        <div class="label">Pangkat kehadiranmu</div>
        <div class="title" id="homeRankTitle">Belum masuk</div>
        <div class="desc" id="homeRankDesc">Masuk ke akunmu lalu mulai absen untuk membangun rekam jejak kehadiran — dari Peserta Baru hingga PKL Teladan.</div>
      </div>
    </div>

    <div class="cta-row" style="justify-content:center;">
      <?php if ($isLoggedIn): ?>
        <a href="<?= edm_url('absen.php') ?>" class="btn btn-gold">Absen Sekarang →</a>
      <?php else: ?>
        <a href="<?= edm_url('daftar.php') ?>" class="btn btn-gold">Daftar Akun →</a>
        <a href="<?= edm_url('masuk.php') ?>" class="btn btn-outline">Masuk</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="section-head">
      <h2>Berita terbaru</h2>
      <a class="num" href="<?= edm_url('berita.php') ?>">lihat semua →</a>
    </div>
    <div class="news-grid" id="homeNewsGrid"></div>
  </div>
</section>

<?php
$pageScript = <<<JS
(async function(){
  const grid = document.getElementById('homeNewsGrid');
  try{
    const res = await api('api/news.php?action=list');
    const items = res.rows.slice(0,3);
    grid.innerHTML = items.length ? items.map(newsCardHTML).join('') : '<p class="empty-note">— belum ada berita —</p>';
  }catch(e){
    grid.innerHTML = '<p class="empty-note">Gagal memuat berita.</p>';
  }

  let distinctCount = 0;
  try{
    const me = await api('api/auth_student.php?action=me');
    if(me.student){
      const mine = await api('api/attendance.php?action=mine');
      distinctCount = new Set(mine.dates).size;
    }
  }catch(e){}
  const rank = computeRank(distinctCount);
  renderChevronRank('homeChevStack', rank.level, 4);
  document.getElementById('homeRankTitle').textContent = rank.title;
  document.getElementById('homeRankDesc').textContent = rank.desc;
})();
JS;
require_once __DIR__ . '/partials/footer.php';
?>