/* Cookie consent — minimal, KVKK/GDPR uyumlu
   Tercihler localStorage'da: { analytics: bool, ts: ISO }
   ────────────────────────────────────────────────── */
(function () {
  const KEY = 'mk-cookie-consent';
  const cfg = window.mkCookieConfig || {};
  let stored = null;
  try { stored = JSON.parse(localStorage.getItem(KEY) || 'null'); } catch (e) {}

  // Google Analytics is only ever loaded from here, after the visitor has consented.
  let analyticsLoaded = false;
  window.mkEnableAnalytics = function () {
    if (analyticsLoaded || !cfg.analyticsId) return;
    analyticsLoaded = true;

    window.dataLayer = window.dataLayer || [];
    window.gtag = function () { window.dataLayer.push(arguments); };
    window.gtag('js', new Date());
    window.gtag('config', cfg.analyticsId, { anonymize_ip: true });

    var tag = document.createElement('script');
    tag.async = true;
    tag.src = 'https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(cfg.analyticsId);
    document.head.appendChild(tag);
  };

  if (stored && stored.ts) {
    // Returning visitor: honour the stored choice instead of asking again.
    if (stored.analytics) window.mkEnableAnalytics();
    return;
  }

  // Only link to legal pages that actually exist; the URLs are null otherwise.
  const linkClass = 'underline hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors';
  const legalLinks = [
    [cfg.cookiePolicyUrl, 'Çerez Politikası'],
    [cfg.kvkkUrl, 'KVKK Aydınlatma Metni']
  ].filter(function (link) { return !!link[0]; })
    .map(function (link) { return '<a href="' + link[0] + '" class="' + linkClass + '">' + link[1] + '</a>'; })
    .join(' ve ');
  const banner = document.createElement('div');
  banner.className = 'fixed left-4 right-4 sm:left-6 sm:right-6 bottom-4 sm:bottom-6 z-[90]';
  banner.setAttribute('role', 'dialog');
  banner.setAttribute('aria-labelledby', 'cookie-title');
  banner.setAttribute('aria-describedby', 'cookie-body');

  banner.style.animation = 'cookieIn .25s cubic-bezier(.2,.9,.3,1)';

  banner.innerHTML = `
    <div class="max-w-5xl mx-auto bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-2xl shadow-[0_8px_32px_rgba(0,0,0,0.08)] dark:shadow-[0_8px_32px_rgba(0,0,0,0.5)] px-5 sm:px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-5">
      <div class="flex-1 min-w-0 flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-3.5">
        <div id="cookie-title" class="flex items-center gap-2 text-sm leading-[22px] font-semibold text-neutral-950 dark:text-neutral-50 whitespace-nowrap">
          <i data-lucide="cookie" class="w-4 h-4 text-neutral-400 dark:text-neutral-500"></i>
          Çerez tercihleri
        </div>
        <p id="cookie-body" class="m-0 text-[13px] leading-[22px] text-neutral-500 dark:text-neutral-400">Site, deneyimi geliştirmek için zorunlu çerezleri kullanır.${legalLinks ? ' ' + legalLinks : ''}</p>
      </div>
      <div class="flex gap-2 flex-shrink-0 w-full sm:w-auto">
        <button type="button" data-cookie="reject"
          class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 text-[13px] font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 bg-transparent text-neutral-950 dark:text-neutral-50 hover:opacity-80 transition-opacity cursor-pointer">
          Reddet
        </button>
        <button type="button" data-cookie="accept"
          class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 text-[13px] font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 hover:opacity-80 transition-opacity cursor-pointer">
          Kabul et
        </button>
      </div>
    </div>
  `;
  document.body.appendChild(banner);
  if (typeof lucide !== 'undefined') lucide.createIcons({ nodes: [banner] });

  function decide(accepted) {
    try {
      localStorage.setItem(KEY, JSON.stringify({
        analytics: accepted,
        functional: true,
        ts: new Date().toISOString()
      }));
    } catch (e) {}
    banner.style.animation = 'cookieOut .2s ease-in forwards';
    setTimeout(function () { banner.remove(); }, 250);
    if (accepted && typeof window.mkEnableAnalytics === 'function') {
      window.mkEnableAnalytics();
    }
  }

  banner.addEventListener('click', function (e) {
    var b = e.target.closest('[data-cookie]');
    if (!b) return;
    decide(b.getAttribute('data-cookie') === 'accept');
  });

  if (!document.getElementById('cookie-keyframes')) {
    var style = document.createElement('style');
    style.id = 'cookie-keyframes';
    style.textContent =
      '@keyframes cookieIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }' +
      '@keyframes cookieOut { to { opacity: 0; transform: translateY(16px); } }';
    document.head.appendChild(style);
  }
})();
