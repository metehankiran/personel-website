import { createIcons, icons } from 'lucide';
import './theme.js';
import './search.js';
import './cookie.js';

window.lucide = { createIcons: (opts) => createIcons({ icons, ...opts }) };
createIcons({ icons });

// Lazy-load secondary fonts after first paint
requestIdleCallback(() => {
    import('@fontsource/jetbrains-mono/400.css');
    import('@fontsource/jetbrains-mono/500.css');
    import('@fontsource/playfair-display/400.css');
    import('@fontsource/playfair-display/500.css');
});
