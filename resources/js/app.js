import * as Turbo from '@hotwired/turbo';

import { initMenu } from './modules/menu';
import { initCarousels } from './modules/carousel';
import { initFilters } from './modules/filters';
import { initDealerModal } from './modules/dealer-modal';
import { initCart } from './modules/cart';

Turbo.config.drive.progressBarDelay = 120;

initMenu();
initDealerModal();
initCart();

document.addEventListener('turbo:load', () => {
    initCarousels();
    initFilters();

    // Respeta "reducir movimiento": el video del inicio no se reproduce solo
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('[data-hero-video]').forEach((video) => video.pause());
    }
});
