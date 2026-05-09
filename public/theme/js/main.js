/* Metehan Kıran — Site JS
   Theme toggle + filter chips + dropdown (CSS handles dropdown hover)
   ────────────────────────────────────────────────── */

(function () {
  // ─── Theme ─────────────────────────────────────────
  const THEME_KEY = 'mk-theme';
  const root = document.documentElement;

  function applyTheme(t) {
    root.setAttribute('data-theme', t);
    const btn = document.querySelector('[data-theme-toggle]');
    if (btn) btn.textContent = t === 'dark' ? '☀' : '☾';
  }

  // Initial theme: stored > prefers-color-scheme > light
  let initial = null;
  try { initial = localStorage.getItem(THEME_KEY); } catch (e) {}
  if (!initial) {
    initial = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }
  applyTheme(initial);

  document.addEventListener('click', function (e) {
    const toggle = e.target.closest('[data-theme-toggle]');
    if (!toggle) return;
    const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    applyTheme(next);
    try { localStorage.setItem(THEME_KEY, next); } catch (err) {}
  });

  // ─── Filter chips ──────────────────────────────────
  // Containers with [data-filter-group="<name>"] hold .chip-lg buttons with [data-filter="<value>"]
  // Items to show/hide carry [data-filter-target="<name>"] and [data-filter-value="<value>"]
  document.addEventListener('click', function (e) {
    const chip = e.target.closest('[data-filter]');
    if (!chip) return;
    const group = chip.closest('[data-filter-group]');
    if (!group) return;
    const name = group.getAttribute('data-filter-group');
    const value = chip.getAttribute('data-filter');

    // toggle active class
    group.querySelectorAll('[data-filter]').forEach(function (c) { c.classList.remove('active'); });
    chip.classList.add('active');

    // show/hide items
    document.querySelectorAll('[data-filter-target="' + name + '"]').forEach(function (item) {
      const v = item.getAttribute('data-filter-value');
      const visible = value === 'all' || v === value;
      item.classList.toggle('is-hidden', !visible);
    });
  });

  // ─── Form submission (placeholder — Laravel will wire this) ──
  document.addEventListener('submit', function (e) {
    if (e.target.matches('[data-mk-form]')) {
      e.preventDefault();
      alert('Mesajın iletildi (demo). Laravel backend\'e bağlandığında çalışacak.');
    }
  });

  // ─── Mark active nav by current pathname ──────────
  const path = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('[data-nav]').forEach(function (el) {
    if (el.getAttribute('data-nav') === path) {
      el.classList.add('active');
      // Also activate parent group if dropdown child
      const group = el.closest('.nav-item');
      if (group) {
        const trigger = group.querySelector('.nav-link');
        if (trigger && trigger !== el) trigger.classList.add('active');
      }
    }
  });
})();
