/* Cookie consent — minimal, KVKK/GDPR uyumlu
   Tercihler localStorage'da: { analytics: bool, ts: ISO }
   ────────────────────────────────────────────────── */
(function () {
  const KEY = 'mk-cookie-consent';
  let stored = null;
  try { stored = JSON.parse(localStorage.getItem(KEY) || 'null'); } catch (e) {}
  if (stored && stored.ts) return; // already decided

  const banner = document.createElement('div');
  banner.className = 'cookie-banner';
  banner.setAttribute('role', 'dialog');
  banner.setAttribute('aria-labelledby', 'cookie-title');
  banner.setAttribute('aria-describedby', 'cookie-body');
  banner.innerHTML = `
    <div class="cookie-card">
      <div class="cookie-icon" aria-hidden="true">🍪</div>
      <div class="cookie-text">
        <div id="cookie-title" class="cookie-title">Çerez tercihleri</div>
        <p id="cookie-body" class="cookie-body">Site, deneyimi geliştirmek için zorunlu çerezleri kullanır. İstersen anonim ziyaretçi istatistikleri toplamamıza da izin verebilirsin.</p>
      </div>
      <div class="cookie-actions">
        <button type="button" class="btn btn-secondary btn-sm" data-cookie="reject">Sadece zorunlu</button>
        <button type="button" class="btn btn-primary btn-sm" data-cookie="accept">Tümünü kabul et</button>
      </div>
    </div>
  `;
  document.body.appendChild(banner);

  function decide(accepted) {
    try {
      localStorage.setItem(KEY, JSON.stringify({
        analytics: accepted,
        functional: true,
        ts: new Date().toISOString()
      }));
    } catch (e) {}
    banner.classList.add('cookie-out');
    setTimeout(() => banner.remove(), 250);
    if (accepted && typeof window.mkEnableAnalytics === 'function') {
      window.mkEnableAnalytics();
    }
  }

  banner.addEventListener('click', (e) => {
    const b = e.target.closest('[data-cookie]');
    if (!b) return;
    decide(b.getAttribute('data-cookie') === 'accept');
  });
})();
