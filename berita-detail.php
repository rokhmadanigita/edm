<?php
$pageTitle = 'Berita — ED Management';
$activePage = 'berita';
require_once __DIR__ . '/partials/header.php';
$newsId = (int) ($_GET['id'] ?? $_GET['phid'] ?? 0);
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
$pageScript = '(async function(){'
  . 'const el = document.getElementById("articleContent");'
  . 'try{'
  . 'const res = await api("api/news.php?action=detail&id=' . (int) $newsId . '");'
  . 'const n = res.news;'
  . 'if (!n) throw new Error("Berita tidak ditemukan.");'
  . 'const bodyHtml = escapeHTML(n.body || "").replace(/\\n/g, "<br>");'
  . 'const title = escapeHTML(n.title || "Berita");'
  . 'const tag = escapeHTML(n.tag || "Berita");'
  . 'const date = escapeHTML(n.date || "");'
  . 'const author = escapeHTML(n.author || "ED Management");'
  . 'const imageHtml = n.photo ? "<img src=\"" + edmUrl("assets/img/" + encodeURIComponent(n.photo)) + "\" alt=\"Foto berita " + title + "\" class=\"article-image\" />" : "";'
  . 'document.title = title + " — ED Management";'
  . 'el.innerHTML = "<span class=\\"news-tag\\">" + tag + "</span>" + "<h1>" + title + "</h1>" + "<div class=\\"meta\\">" + date + " · " + author + "</div>" + imageHtml + "<div class=\\"body-text\\">" + bodyHtml + "</div>";'
  . '}catch(e){'
  . 'el.innerHTML = "<p class=\\"empty-note\\">Berita tidak ditemukan.</p>";'
  . '}'
  . '})();';
require_once __DIR__ . '/partials/footer.php';
?>
