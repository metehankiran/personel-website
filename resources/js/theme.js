const THEME_KEY = 'mk-theme';
const root = document.documentElement;
const mq = window.matchMedia('(prefers-color-scheme: dark)');

const activeClass = 'bg-white dark:bg-neutral-800 text-neutral-950 dark:text-neutral-50 shadow-sm';
const inactiveClass = 'text-neutral-400 dark:text-neutral-500 hover:text-neutral-600 dark:hover:text-neutral-300';

function resolveTheme(mode) {
    if (mode === 'system') return mq.matches ? 'dark' : 'light';
    return mode;
}

function applyTheme(mode) {
    root.setAttribute('data-theme', resolveTheme(mode));
    document.querySelectorAll('[data-theme-set]').forEach((btn) => {
        const isActive = btn.getAttribute('data-theme-set') === mode;
        activeClass.split(' ').forEach((c) => btn.classList.toggle(c, isActive));
        inactiveClass.split(' ').forEach((c) => btn.classList.toggle(c, !isActive));
    });
}

let stored = null;
try { stored = localStorage.getItem(THEME_KEY); } catch {}
applyTheme(stored || 'system');

mq.addEventListener('change', () => {
    let mode = null;
    try { mode = localStorage.getItem(THEME_KEY); } catch {}
    if (!mode || mode === 'system') applyTheme('system');
});

document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-theme-set]');
    if (!btn) return;
    const mode = btn.getAttribute('data-theme-set');
    applyTheme(mode);
    try { localStorage.setItem(THEME_KEY, mode); } catch {}
});

// Filter chips
document.addEventListener('click', (e) => {
    const chip = e.target.closest('[data-filter]');
    if (!chip) return;
    const group = chip.closest('[data-filter-group]');
    if (!group) return;
    const name = group.getAttribute('data-filter-group');
    const value = chip.getAttribute('data-filter');

    group.querySelectorAll('[data-filter]').forEach((c) => c.classList.remove('active'));
    chip.classList.add('active');

    document.querySelectorAll(`[data-filter-target="${name}"]`).forEach((item) => {
        const v = item.getAttribute('data-filter-value');
        item.classList.toggle('is-hidden', value !== 'all' && v !== value);
    });
});
