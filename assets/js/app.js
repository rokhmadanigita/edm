function edmUrl(path){
  const clean = String(path || '').replace(/^\/+/, '');
  const inferredBase = window.__EDM_BASE__ || window.location.pathname.replace(/\/[^/]*$/, '');
  const base = String(inferredBase || '').replace(/\/$/, '');
  return base ? `${base}/${clean}` : `/${clean}`;
}

async function api(path, method, body){
  const url = String(path).match(/^https?:\/\//) ? path : edmUrl(path);
  const opts = { method: method || 'GET', headers: {'Content-Type':'application/json'}, credentials:'same-origin' };
  if(body !== undefined) opts.body = JSON.stringify(body);

  let res;
  try{
    res = await fetch(url, opts);
  }catch(e){
    throw new Error('Tidak bisa terhubung ke server. Periksa koneksi atau pengaturan api/config.php.');
  }

  let data;
  try{
    data = await res.json();
  }catch(e){
    const text = await res.text().catch(() => '');
    throw new Error(text || 'Respons server tidak valid.');
  }

  if(!res.ok){ throw new Error(data.error || 'Terjadi kesalahan.'); }
  return data;
}

function escapeHTML(s){
  const d = document.createElement('div');
  d.textContent = s == null ? '' : s;
  return d.innerHTML;
}

function setupPasswordToggles(root = document){
  const eyeOpen = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
  const eyeClosed = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"></path><path d="M10.5 10.5a3 3 0 0 0 4.2 4.2"></path><path d="M9.8 5.1A11.2 11.2 0 0 1 12 5c6 0 9.5 7 9.5 7a18.6 18.6 0 0 1-3.2 4.1"></path><path d="M6.1 6.1A18.7 18.7 0 0 0 2 12s3.5 6 10 6c1.2 0 2.2-.2 3.1-.5"></path></svg>';

  root.querySelectorAll('.password-field').forEach((wrap) => {
    const input = wrap.querySelector('input[type="password"], input[type="text"]');
    const button = wrap.querySelector('.password-toggle');
    if (!input || !button) return;

    button.innerHTML = eyeOpen;
    button.setAttribute('aria-label', 'Tampilkan kata sandi');
    button.title = 'Tampilkan kata sandi';

    button.addEventListener('click', () => {
      const showing = input.type === 'text';
      input.type = showing ? 'password' : 'text';
      button.innerHTML = showing ? eyeOpen : eyeClosed;
      button.setAttribute('aria-label', showing ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
      button.title = showing ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi';
    });
  });
}

function todayStr(){ return new Date().toLocaleDateString('id-ID', {year:'numeric', month:'long', day:'numeric'}); }
function nowTime(){ return new Date().toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}); }

// "Pangkat kehadiran" — dipakai di index.php dan absen.php
function computeRank(totalDays){
  if(totalDays <= 0) return {level:0, title:'Belum ada data', desc:'Absen hari ini untuk mulai membangun rekam jejak kehadiranmu. Semakin konsisten, semakin tinggi pangkatnya — dari Peserta Baru hingga PKL Teladan.'};
  if(totalDays < 3) return {level:1, title:'Peserta Baru', desc:'Baru mulai mencatat kehadiran. Terus absen setiap hari kerja untuk naik pangkat.'};
  if(totalDays < 7) return {level:2, title:'PKL Aktif', desc:'Kehadiran mulai konsisten. Pertahankan ritme ini sampai akhir minggu.'};
  if(totalDays < 14) return {level:3, title:'PKL Konsisten', desc:'Rekam jejak kehadiran sudah solid. Tim menghargai konsistensi ini.'};
  return {level:4, title:'PKL Teladan', desc:'Kehadiran sangat konsisten — pangkat tertinggi di ED Management.'};
}

function renderChevronRank(containerId, filled, count){
  count = count || 4;
  const el = document.getElementById(containerId);
  if(!el) return;
  el.innerHTML = '';
  for(let i=0;i<count;i++){
    const div = document.createElement('div');
    div.className = 'chev' + (i < filled ? ' filled' : '');
    el.appendChild(div);
  }
}

function newsCardHTML(n){
  const cover = n.photo ? `<img src="${edmUrl('assets/img/' + encodeURIComponent(n.photo))}" alt="Berita ${escapeHTML(n.title)}" />` : `<svg viewBox="0 0 60 44" fill="none"><path d="M8 34 L30 12 L52 34" stroke="#F3F1EA" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" opacity="0.6"/><path d="M8 44 L30 22 L52 44" stroke="#F3F1EA" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
  return `
    <a href="${edmUrl('berita-detail.php?id=' + n.id)}" class="news-card" style="text-decoration:none;">
      <div class="news-cover">${cover}</div>
      <div class="news-body">
        <span class="news-tag">${escapeHTML(n.tag || 'Berita')}</span>
        <h3>${escapeHTML(n.title)}</h3>
        <p class="news-excerpt">${escapeHTML((n.body||'').slice(0,90))}${(n.body||'').length>90?'…':''}</p>
        <div class="news-meta">${escapeHTML(n.date)} · ${escapeHTML(n.author || 'ED Management')}</div>
      </div>
    </a>`;
}
