/* Spotlight-style global search
   ─ Open: Cmd/Ctrl+K, "/" hotkey, or click search trigger
   ─ Close: Esc, click backdrop, click result
   ─ Keyboard nav: ↑↓ to move, Enter to open
   ─ Mock data lives in MK_SEARCH_INDEX — replace with backend payload later
   ────────────────────────────────────────────────── */

(function () {
  // ─── Mock search index (replace with backend data) ────
  // Each item: { type, title, desc, url, tags? }
  const MK_SEARCH_INDEX = [
    // Pages
    { type: 'Sayfa', title: 'Ana sayfa', desc: 'Hero, öne çıkan proje ve özet', url: 'index.html' },
    { type: 'Sayfa', title: 'Hakkımda', desc: 'Biyografi, zaman çizelgesi', url: 'hakkimda.html' },
    { type: 'Sayfa', title: 'Projeler', desc: 'Tüm freelance işler', url: 'projeler.html' },
    { type: 'Sayfa', title: 'Hizmetler', desc: 'Çalışma şekilleri ve fiyatlandırma', url: 'hizmetler.html' },
    { type: 'Sayfa', title: 'Stack', desc: 'Kullandığım teknolojiler', url: 'stack.html' },
    { type: 'Sayfa', title: 'Referanslar', desc: 'Müşteri yorumları ve markalar', url: 'referanslar.html' },
    { type: 'Sayfa', title: 'Blog', desc: 'Teknik makaleler', url: 'blog.html' },
    { type: 'Sayfa', title: 'Notlar', desc: 'Kısa düşünceler, now-page', url: 'notlar.html' },
    { type: 'Sayfa', title: 'Uses', desc: 'Setup ve araçlar', url: 'uses.html' },
    { type: 'Sayfa', title: 'CV', desc: 'Özgeçmiş, PDF indir', url: 'cv.html' },
    { type: 'Sayfa', title: 'İletişim', desc: 'Email, sosyal, form', url: 'iletisim.html' },

    // Projects
    { type: 'Proje', title: 'Karavela', desc: 'Multi-tenant e-ticaret SaaS', url: 'projeler.html#karavela', tags: ['saas','laravel','vue'] },
    { type: 'Proje', title: 'Flotaki', desc: 'Filo yönetim CRM', url: 'projeler.html#flotaki', tags: ['crm','dotnet'] },
    { type: 'Proje', title: 'Rezerv', desc: 'Restoran rezervasyon API', url: 'projeler.html#rezerv', tags: ['api','laravel','redis'] },
    { type: 'Proje', title: 'Atelye', desc: 'Mimarlık ofisi sitesi', url: 'projeler.html#atelye', tags: ['web','nuxt'] },
    { type: 'Proje', title: 'Tezgah', desc: 'Mobil pazaryeri API', url: 'projeler.html#tezgah', tags: ['api','laravel'] },
    { type: 'Proje', title: 'Patika', desc: 'E-eğitim platformu', url: 'projeler.html#patika', tags: ['saas','vue','s3'] },

    // Blog posts
    { type: 'Yazı', title: "Laravel'de gerçekten lazım olan paketler", desc: 'Her projede kurduğum 7 paket', url: 'blog.html', tags: ['laravel'] },
    { type: 'Yazı', title: '.NET Core ile API yazarken yaptığım 5 hata', desc: 'Laravel arka planından gelirken', url: 'blog.html', tags: ['dotnet'] },
    { type: 'Yazı', title: 'Vue 3 Composition API: pratiğe geçince', desc: 'Bir yıl sonra notlar', url: 'blog.html', tags: ['vue'] },
    { type: 'Yazı', title: 'Multi-tenant SaaS: tek veritabanı ya da kiracı başına?', desc: 'Karavela kararı', url: 'blog.html', tags: ['mimari','saas'] },
    { type: 'Yazı', title: 'Freelance ilk yılım — açık rakamlarla', desc: '12 ay, 18 müşteri', url: 'blog.html', tags: ['freelance'] },
    { type: 'Yazı', title: "Production'da Docker: faydalı 6 pattern", desc: 'Geliştirme dışında Docker', url: 'blog.html', tags: ['devops','docker'] },

    // Quick actions
    { type: 'Eylem', title: 'Email gönder', desc: 'merhaba@metehankiran.dev', url: 'mailto:merhaba@metehankiran.dev' },
    { type: 'Eylem', title: 'CV indir', desc: 'PDF olarak özgeçmiş', url: 'cv.html' },
    { type: 'Eylem', title: 'GitHub', desc: 'github.com/metehankiran', url: 'https://github.com/' },
    { type: 'Eylem', title: 'LinkedIn', desc: 'in/metehankiran', url: 'https://linkedin.com/' },
    { type: 'Eylem', title: 'Tema değiştir', desc: 'Açık ↔ koyu mod', url: '#toggle-theme' },
  ];
  window.MK_SEARCH_INDEX = MK_SEARCH_INDEX;

  // ─── Build modal DOM (once) ───────────────────────
  const modal = document.createElement('div');
  modal.className = 'spotlight';
  modal.setAttribute('hidden', '');
  modal.setAttribute('role', 'dialog');
  modal.setAttribute('aria-modal', 'true');
  modal.setAttribute('aria-label', 'Sitede ara');
  modal.innerHTML = `
    <div class="spotlight-backdrop" data-spotlight-close></div>
    <div class="spotlight-panel" role="document">
      <div class="spotlight-input-row">
        <svg class="spotlight-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true">
          <circle cx="8" cy="8" r="5.5"/><path d="M12 12L16 16"/>
        </svg>
        <input type="search" class="spotlight-input"
               placeholder="Sitede ara — sayfalar, projeler, yazılar…"
               autocomplete="off" spellcheck="false"
               aria-label="Arama" aria-controls="spotlight-results"
               aria-activedescendant="" />
        <kbd class="spotlight-kbd" aria-hidden="true">esc</kbd>
      </div>
      <div class="spotlight-results" id="spotlight-results" role="listbox" aria-label="Sonuçlar"></div>
      <div class="spotlight-footer">
        <span><kbd>↑</kbd><kbd>↓</kbd> dolaş</span>
        <span><kbd>↵</kbd> aç</span>
        <span><kbd>esc</kbd> kapat</span>
      </div>
    </div>`;
  document.body.appendChild(modal);

  const input = modal.querySelector('.spotlight-input');
  const resultsEl = modal.querySelector('.spotlight-results');
  let cursor = 0;
  let lastFocus = null;
  let currentResults = [];

  // ─── Search ranking ────────────────────────────────
  function rank(query) {
    if (!query) {
      // Default: show grouped quick set (a few of each type)
      const wanted = ['Sayfa', 'Proje', 'Yazı', 'Eylem'];
      const out = [];
      for (const t of wanted) {
        MK_SEARCH_INDEX.filter(i => i.type === t).slice(0, 4).forEach(i => out.push({ item: i, score: 1 }));
      }
      return out;
    }
    const q = query.toLowerCase().trim();
    const scored = MK_SEARCH_INDEX.map(item => {
      const hay = (item.title + ' ' + item.desc + ' ' + (item.tags || []).join(' ')).toLowerCase();
      let score = 0;
      if (item.title.toLowerCase() === q) score += 100;
      if (item.title.toLowerCase().startsWith(q)) score += 60;
      if (hay.includes(q)) score += 30;
      // word-by-word
      q.split(/\s+/).forEach(part => { if (part && hay.includes(part)) score += 5; });
      return { item, score };
    }).filter(r => r.score > 0);
    scored.sort((a, b) => b.score - a.score);
    return scored.slice(0, 30);
  }

  // ─── Render ────────────────────────────────────────
  function highlight(text, q) {
    if (!q) return escapeHtml(text);
    const lower = text.toLowerCase();
    const idx = lower.indexOf(q.toLowerCase());
    if (idx < 0) return escapeHtml(text);
    return escapeHtml(text.slice(0, idx)) +
      '<mark>' + escapeHtml(text.slice(idx, idx + q.length)) + '</mark>' +
      escapeHtml(text.slice(idx + q.length));
  }
  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, c => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c]));
  }

  function render() {
    const q = input.value.trim();
    currentResults = rank(q);
    if (!currentResults.length) {
      resultsEl.innerHTML = `<div class="spotlight-empty">"${escapeHtml(q)}" için sonuç yok</div>`;
      input.setAttribute('aria-activedescendant', '');
      return;
    }
    // Group by type
    const groups = {};
    currentResults.forEach((r, i) => {
      (groups[r.item.type] ||= []).push({ ...r, i });
    });
    let html = '';
    Object.keys(groups).forEach(type => {
      html += `<div class="spotlight-group-title">${escapeHtml(type)}</div>`;
      groups[type].forEach(r => {
        const id = 'sp-r-' + r.i;
        const sel = r.i === cursor ? ' is-active' : '';
        html += `<button type="button" class="spotlight-item${sel}"
                  id="${id}" role="option" aria-selected="${r.i === cursor}"
                  data-idx="${r.i}" data-url="${escapeHtml(r.item.url)}">
          <span class="spotlight-item-title">${highlight(r.item.title, q)}</span>
          <span class="spotlight-item-desc">${highlight(r.item.desc, q)}</span>
          <span class="spotlight-item-arrow" aria-hidden="true">↵</span>
        </button>`;
      });
    });
    resultsEl.innerHTML = html;
    const active = resultsEl.querySelector('.spotlight-item.is-active');
    if (active) {
      input.setAttribute('aria-activedescendant', active.id);
      active.scrollIntoView({ block: 'nearest' });
    }
  }

  // ─── Open / close ─────────────────────────────────
  function open() {
    if (!modal.hasAttribute('hidden')) return;
    lastFocus = document.activeElement;
    modal.removeAttribute('hidden');
    document.body.style.overflow = 'hidden';
    cursor = 0;
    input.value = '';
    render();
    requestAnimationFrame(() => input.focus());
  }
  function close() {
    if (modal.hasAttribute('hidden')) return;
    modal.setAttribute('hidden', '');
    document.body.style.overflow = '';
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  }

  // ─── Activate result ──────────────────────────────
  function activate(idx) {
    const r = currentResults[idx];
    if (!r) return;
    const url = r.item.url;
    if (url === '#toggle-theme') {
      close();
      const btn = document.querySelector('[data-theme-toggle]');
      if (btn) btn.click();
      return;
    }
    close();
    if (/^https?:|^mailto:|^tel:/.test(url)) {
      window.location.href = url;
    } else {
      window.location.href = url;
    }
  }

  // ─── Wire up ──────────────────────────────────────
  input.addEventListener('input', () => { cursor = 0; render(); });

  modal.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      if (currentResults.length) { cursor = (cursor + 1) % currentResults.length; render(); }
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      if (currentResults.length) { cursor = (cursor - 1 + currentResults.length) % currentResults.length; render(); }
    } else if (e.key === 'Enter') {
      e.preventDefault();
      activate(cursor);
    } else if (e.key === 'Escape') {
      e.preventDefault();
      close();
    } else if (e.key === 'Tab') {
      // trap focus inside input (the only focusable, results are activated via keyboard cursor)
      e.preventDefault();
    }
  });

  resultsEl.addEventListener('click', (e) => {
    const item = e.target.closest('.spotlight-item');
    if (!item) return;
    activate(parseInt(item.getAttribute('data-idx'), 10));
  });
  resultsEl.addEventListener('mousemove', (e) => {
    const item = e.target.closest('.spotlight-item');
    if (!item) return;
    const idx = parseInt(item.getAttribute('data-idx'), 10);
    if (idx !== cursor) { cursor = idx; render(); }
  });

  modal.addEventListener('click', (e) => {
    if (e.target.matches('[data-spotlight-close]')) close();
  });

  // Global hotkeys: Cmd/Ctrl+K, "/"
  document.addEventListener('keydown', (e) => {
    const isShortcut = (e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k';
    const isSlash = e.key === '/' && !modal.hasAttribute('hidden') === false &&
      !/^(input|textarea|select)$/i.test((document.activeElement || {}).tagName || '');
    if (isShortcut) { e.preventDefault(); open(); }
    else if (isSlash) { e.preventDefault(); open(); }
  });

  // Trigger button(s)
  document.addEventListener('click', (e) => {
    if (e.target.closest('[data-search-trigger]')) {
      e.preventDefault();
      open();
    }
  });

  // Expose for debugging / external triggers
  window.MKSearch = { open, close };
})();
