// Arrastre con mouse para el carrusel (en touch se usa el scroll nativo).

export function initCarousels() {
    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        const track = carousel.querySelector('[data-carousel-track]');

        if (!track || track.dataset.ready) return;
        track.dataset.ready = 'true';

        let startX = 0;
        let startScroll = 0;
        let moved = false;

        const onMove = (event) => {
            const delta = event.clientX - startX;

            if (Math.abs(delta) > 4) {
                moved = true;
                carousel.classList.add('is-dragging');
            }

            track.scrollLeft = startScroll - delta;
        };

        const onUp = () => {
            window.removeEventListener('pointermove', onMove);
            carousel.classList.remove('is-dragging');
        };

        track.addEventListener('pointerdown', (event) => {
            if (event.pointerType !== 'mouse' || event.button !== 0) return;

            startX = event.clientX;
            startScroll = track.scrollLeft;
            moved = false;

            window.addEventListener('pointermove', onMove);
            window.addEventListener('pointerup', onUp, { once: true });
        });

        // Evita abrir la ficha al soltar después de arrastrar
        track.addEventListener('click', (event) => {
            if (moved) {
                event.preventDefault();
                moved = false;
            }
        }, true);

        track.addEventListener('dragstart', (event) => event.preventDefault());
    });
}
