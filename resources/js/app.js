import * as Turbo from '@hotwired/turbo';

import { initMenu } from './modules/menu';
import { initCarousels } from './modules/carousel';
import { initFilters } from './modules/filters';

Turbo.config.drive.progressBarDelay = 120;

initMenu();

document.addEventListener('turbo:load', () => {
    initCarousels();
    initFilters();
});
