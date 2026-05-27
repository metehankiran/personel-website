import { createIcons, icons } from 'lucide';

let MK_SEARCH_INDEX = [];
let indexLoaded = false;

function loadIndex() {
    if (indexLoaded) return Promise.resolve();
    const url = window.mkSearchUrl;
    if (!url) return Promise.resolve();
    return fetch(url, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then((r) => r.json())
        .then((data) => { MK_SEARCH_INDEX = data; indexLoaded = true; })
        .catch(() => {});
}

const styleEl = document.createElement('style');
styleEl.textContent = '@keyframes spotlight-pop{from{opacity:0;transform:translateY(-8px) scale(.98)}to{opacity:1;transform:none}}';
document.head.appendChild(styleEl);

const modal = document.createElement('div');
modal.className = 'fixed inset-0 z-[200] items-start justify-center pt-[12vh]';
modal.style.display = 'none';
modal.setAttribute('role', 'dialog');
modal.setAttribute('aria-modal', 'true');
modal.setAttribute('aria-label', 'Sitede ara');
modal.innerHTML = `
  <div class="absolute inset-0 bg-neutral-950/20 backdrop-blur-[20px]" style="backdrop-filter:blur(20px) saturate(140%);-webkit-backdrop-filter:blur(20px) saturate(140%)" data-spotlight-close></div>
  <div class="relative bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-2xl overflow-hidden flex flex-col max-h-[70vh] w-[min(640px,92vw)]" style="box-shadow:0 24px 80px rgba(0,0,0,0.25),0 4px 16px rgba(0,0,0,0.1);animation:spotlight-pop .18s cubic-bezier(.2,.9,.3,1.2)" role="document">
    <div class="flex items-center gap-3 px-5 py-[18px] border-b border-neutral-200 dark:border-neutral-800">
      <svg class="text-neutral-600 dark:text-neutral-400 shrink-0" width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true">
        <circle cx="8" cy="8" r="5.5"/><path d="M12 12L16 16"/>
      </svg>
      <input type="search" class="spotlight-input flex-1 min-w-0 text-[17px] font-sans bg-transparent text-neutral-950 dark:text-neutral-50 border-none outline-none p-0 placeholder:text-neutral-400 dark:placeholder:text-neutral-600"
             placeholder="Sitede ara — sayfalar, projeler, yazılar…"
             autocomplete="off" spellcheck="false"
             aria-label="Arama" aria-controls="spotlight-results"
             aria-activedescendant="" />
      <kbd class="font-sans text-[11px] px-[7px] py-[3px] rounded bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-800 shrink-0" aria-hidden="true">esc</kbd>
    </div>
    <div class="spotlight-results flex-1 overflow-y-auto p-2" id="spotlight-results" role="listbox" aria-label="Sonuçlar"></div>
    <div class="flex gap-[18px] px-[18px] py-3 border-t border-neutral-200 dark:border-neutral-800 text-xs text-neutral-500 bg-neutral-50 dark:bg-neutral-900">
      <span><kbd class="font-sans text-[10px] px-[5px] py-[1px] rounded-[3px] bg-white dark:bg-neutral-950 text-neutral-600 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-800 mr-1">↑</kbd><kbd class="font-sans text-[10px] px-[5px] py-[1px] rounded-[3px] bg-white dark:bg-neutral-950 text-neutral-600 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-800 mr-1">↓</kbd> dolaş</span>
      <span><kbd class="font-sans text-[10px] px-[5px] py-[1px] rounded-[3px] bg-white dark:bg-neutral-950 text-neutral-600 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-800 mr-1">↵</kbd> aç</span>
      <span><kbd class="font-sans text-[10px] px-[5px] py-[1px] rounded-[3px] bg-white dark:bg-neutral-950 text-neutral-600 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-800 mr-1">esc</kbd> kapat</span>
    </div>
  </div>`;
document.body.appendChild(modal);

const input = modal.querySelector('.spotlight-input');
const resultsEl = modal.querySelector('.spotlight-results');
let cursor = 0;
let lastFocus = null;
let currentResults = [];

const groupIcons = { Sayfa: 'compass', Proje: 'folder-open', Yazı: 'pen-line', 'Hızlı erişim': 'zap' };

function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
}

function highlight(text, q) {
    if (!q) return escapeHtml(text);
    const idx = text.toLowerCase().indexOf(q.toLowerCase());
    if (idx < 0) return escapeHtml(text);
    return escapeHtml(text.slice(0, idx)) +
        '<mark class="bg-green-500/25 text-neutral-950 dark:text-neutral-50 px-0.5 rounded-sm">' + escapeHtml(text.slice(idx, idx + q.length)) + '</mark>' +
        escapeHtml(text.slice(idx + q.length));
}

function rank(query) {
    if (!query) {
        const wanted = ['Sayfa', 'Proje', 'Yazı', 'Hızlı erişim'];
        const out = [];
        for (const t of wanted) {
            MK_SEARCH_INDEX.filter((i) => i.type === t).slice(0, 4).forEach((i) => out.push({ item: i, score: 1 }));
        }
        return out;
    }
    const q = query.toLowerCase().trim();
    return MK_SEARCH_INDEX.map((item) => {
        const hay = (item.title + ' ' + item.desc + ' ' + (item.tags || []).join(' ')).toLowerCase();
        let score = 0;
        if (item.title.toLowerCase() === q) score += 100;
        if (item.title.toLowerCase().startsWith(q)) score += 60;
        if (hay.includes(q)) score += 30;
        q.split(/\s+/).forEach((part) => { if (part && hay.includes(part)) score += 5; });
        return { item, score };
    }).filter((r) => r.score > 0).sort((a, b) => b.score - a.score).slice(0, 30);
}

function render() {
    const q = input.value.trim();
    currentResults = rank(q);
    if (!currentResults.length) {
        resultsEl.innerHTML = '<div class="py-8 px-4 text-center text-neutral-600 dark:text-neutral-400 text-sm">"' + escapeHtml(q) + '" için sonuç yok</div>';
        input.setAttribute('aria-activedescendant', '');
        return;
    }
    const groups = {};
    currentResults.forEach((r, i) => { (groups[r.item.type] ||= []).push({ ...r, i }); });

    let html = '';
    Object.keys(groups).forEach((type) => {
        const gIcon = groupIcons[type] || 'file';
        html += `<div class="flex items-center gap-1.5 text-[11px] text-neutral-500 tracking-[1.2px] uppercase px-3 pt-3.5 pb-1.5"><i data-lucide="${escapeHtml(gIcon)}" class="w-3.5 h-3.5"></i>${escapeHtml(type)}</div>`;
        groups[type].forEach((r) => {
            const id = 'sp-r-' + r.i;
            const active = r.i === cursor ? ' bg-neutral-100 dark:bg-neutral-900' : '';
            const arrow = r.i === cursor ? 'opacity-100 text-neutral-600 dark:text-neutral-400' : 'opacity-0 text-neutral-400';
            html += `<button type="button" class="spotlight-item flex items-center gap-3 w-full px-3 py-2.5 border-none bg-transparent rounded-lg text-left cursor-pointer font-sans text-neutral-950 dark:text-neutral-50${active}" id="${id}" role="option" aria-selected="${r.i === cursor}" data-idx="${r.i}" data-url="${escapeHtml(r.item.url)}"><div class="flex-1 min-w-0"><div class="text-sm font-medium">${highlight(r.item.title, q)}</div><div class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5 overflow-hidden text-ellipsis whitespace-nowrap">${highlight(r.item.desc, q)}</div></div><span class="text-sm shrink-0 ${arrow}" aria-hidden="true">↵</span></button>`;
        });
    });
    resultsEl.innerHTML = html;
    createIcons({ icons, nodes: [resultsEl] });
    const active = resultsEl.querySelector('.spotlight-item[aria-selected="true"]');
    if (active) {
        input.setAttribute('aria-activedescendant', active.id);
        active.scrollIntoView({ block: 'nearest' });
    }
}

function open() {
    if (modal.style.display === 'flex') return;
    lastFocus = document.activeElement;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    cursor = 0;
    input.value = '';
    loadIndex().then(() => render());
    requestAnimationFrame(() => input.focus());
}

function close() {
    if (modal.style.display === 'none') return;
    modal.style.display = 'none';
    document.body.style.overflow = '';
    if (lastFocus && lastFocus.focus) lastFocus.focus();
}

function activate(idx) {
    const r = currentResults[idx];
    if (!r) return;
    if (r.item.url === '#toggle-theme') {
        close();
        const btn = document.querySelector('[data-theme-set]');
        if (btn) btn.click();
        return;
    }
    close();
    window.location.href = r.item.url;
}

input.addEventListener('input', () => { cursor = 0; render(); });

modal.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowDown') { e.preventDefault(); if (currentResults.length) { cursor = (cursor + 1) % currentResults.length; render(); } }
    else if (e.key === 'ArrowUp') { e.preventDefault(); if (currentResults.length) { cursor = (cursor - 1 + currentResults.length) % currentResults.length; render(); } }
    else if (e.key === 'Enter') { e.preventDefault(); activate(cursor); }
    else if (e.key === 'Escape') { e.preventDefault(); close(); }
    else if (e.key === 'Tab') { e.preventDefault(); }
});

resultsEl.addEventListener('click', (e) => { const item = e.target.closest('.spotlight-item'); if (item) activate(parseInt(item.dataset.idx, 10)); });
resultsEl.addEventListener('mousemove', (e) => { const item = e.target.closest('.spotlight-item'); if (item) { const idx = parseInt(item.dataset.idx, 10); if (idx !== cursor) { cursor = idx; render(); } } });
modal.addEventListener('click', (e) => { if (e.target.matches('[data-spotlight-close]')) close(); });

document.addEventListener('keydown', (e) => {
    const isShortcut = (e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k';
    const isSlash = e.key === '/' && modal.style.display === 'none' && !/^(input|textarea|select)$/i.test((document.activeElement || {}).tagName || '');
    if (isShortcut) { e.preventDefault(); open(); }
    else if (isSlash) { e.preventDefault(); open(); }
});

document.addEventListener('click', (e) => { if (e.target.closest('[data-search-trigger]')) { e.preventDefault(); open(); } });

window.MKSearch = { open, close };
