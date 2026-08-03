<?php
$pageTitle = 'Berita — ED Management';
$activePage = 'berita';
require_once __DIR__ . '/partials/header.php';
$newsId = (int) ($_GET['id'] ?? 0);
?>

<section style="padding-top:56px;">
  <div class="wrap article-view">
    <a href="<?= edm_url('berita.php') ?>" class="back-link">← kembali ke berita</a>
    <div id="articleContent">
      <p class="empty-note">Memuat berita…</p>
    </div>
  </div>
</section>

<?php
$pageScript = <<<'JS'
(async function(){
  const el = document.getElementById('articleContent');
  try{
    const res = await api('api/news.php?action=detail&id=<?= (int) $newsId ?>');
    const n = res.news;
    document.title = n.title + ' — ED Management';
    el.innerHTML = `
      <span class="news-tag">${escapeHTML(n.tag||'Berita')}</span>
      <h1>${escapeHTML(n.title)}</h1>
      <div class="meta">${escapeHTML(n.date)} · ${escapeHTML(n.author || 'ED Management')}</div>
      ${n.photo ? `<img src="${edm_url('assets/img/' + encodeURIComponent(n.photo))}" alt="Foto berita ${escapeHTML(n.title)}" class="article-image" />` : ''}
      <div class="body-text">${escapeHTML(n.body)}</div>
    `;
  }catch(e){
    el.innerHTML = '<p class="empty-note">Berita tidak ditemukan.</p>';
  }
})();
JS;
require_once __DIR__ . '/partials/footer.php';
?>
