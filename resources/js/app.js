import { createIcons, icons } from 'lucide';
import './theme.js';
import './search.js';
import './cookie.js';

window.lucide = { createIcons: (opts) => createIcons({ icons, ...opts }) };
createIcons({ icons });
