<?php
$pageTitle = 'Berita — ED Management';
$activePage = 'berita';
require_once __DIR__ . '/partials/header.php';
?>

<section style="padding-top:56px;">
  <div class="wrap">
    <div class="eyebrow">Kabar dari lapangan</div>
    <h1 style="font-size:clamp(30px,4.5vw,44px); margin-bottom:32px;">Berita PKL</h1>
    <div class="news-grid" id="beritaGrid"></div>
    <p class="empty-note" id="beritaEmpty" style="display:none; padding:24px 0;">— belum ada berita yang diterbitkan —</p>
  </div>
</section>

<?php
$pageScript = <<<JS
(async function(){
  const grid = document.getElementById('beritaGrid');
  const empty = document.getElementById('beritaEmpty');
  try{
    const res = await api('api/news.php?action=list');
    if(res.rows.length === 0){ empty.style.display = 'block'; return; }
    grid.innerHTML = res.rows.map(newsCardHTML).join('');
  }catch(e){
    empty.style.display = 'block';
    empty.textContent = 'Gagal memuat berita: ' + e.message;
  }
})();
JS;
require_once __DIR__ . '/partials/footer.php';
?>
