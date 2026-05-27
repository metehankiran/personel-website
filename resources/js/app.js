import { createIcons, icons } from 'lucide';

window.lucide = { createIcons: (opts) => createIcons({ icons, ...opts }) };

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});
