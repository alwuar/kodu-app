// Formulario emergente "¿Quieres ser distribuidor?".
// Cualquier enlace o botón con [data-dealer-open] lo abre. Sin JS, el enlace
// lleva a la página /distribuidores.

const modal = () => document.querySelector('[data-dealer-modal]');

function open(dialog) {
    if (!dialog || dialog.open) return;
    dialog.showModal();
    document.body.classList.add('has-modal');
    dialog.querySelector('input:not([type=hidden])')?.focus({ preventScroll: true });
}

function close(dialog) {
    if (!dialog?.open) return;
    dialog.close();
}

export function initDealerModal() {
    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-dealer-open]');

        if (trigger) {
            // En la página de distribuidores el formulario ya está ahí
            const pageForm = document.getElementById('solicitud');
            event.preventDefault();

            if (pageForm) {
                pageForm.scrollIntoView({ behavior: 'smooth' });
                return;
            }

            open(modal());
            return;
        }

        if (event.target.closest('[data-dealer-close]')) {
            close(modal());
            return;
        }

        // Clic fuera del panel (en el fondo oscuro)
        const dialog = modal();
        if (dialog?.open && event.target === dialog) close(dialog);
    });

    document.addEventListener('close', (event) => {
        if (event.target.matches?.('[data-dealer-modal]')) {
            document.body.classList.remove('has-modal');
        }
    }, true);

    document.addEventListener('turbo:load', () => {
        const dialog = modal();
        if (dialog?.hasAttribute('data-open')) open(dialog);
    });

    document.addEventListener('turbo:before-cache', () => close(modal()));
}
