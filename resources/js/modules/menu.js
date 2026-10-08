// Menú móvil. Los eventos se delegan al documento, así funcionan
// después de cada navegación de Turbo sin volver a enlazarlos.

const close = () => {
    const toggle = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-menu]');

    toggle?.setAttribute('aria-expanded', 'false');
    menu?.classList.remove('is-open');
};

export function initMenu() {
    document.addEventListener('click', (event) => {
        const toggle = event.target.closest('[data-menu-toggle]');

        if (toggle) {
            const menu = document.querySelector('[data-menu]');
            const open = toggle.getAttribute('aria-expanded') !== 'true';

            toggle.setAttribute('aria-expanded', String(open));
            menu?.classList.toggle('is-open', open);
            return;
        }

        if (event.target.closest('[data-menu] a') || !event.target.closest('[data-header]')) {
            close();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') close();
    });

    // Que el menú no quede abierto en la copia que Turbo guarda en caché
    document.addEventListener('turbo:before-cache', close);
}
