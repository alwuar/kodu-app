// Panel de administración: lista de especificaciones, URL automática y confirmaciones.

const slugify = (text) => text
    .toString()
    .normalize('NFD')
    .replace(/[̀-ͯ]/g, '')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');

// URL (slug) automática a partir del nombre mientras no se edite a mano
document.querySelectorAll('[data-slug-source]').forEach((source) => {
    const target = document.querySelector(source.dataset.slugSource);
    if (!target) return;

    let touched = target.value !== '' && target.value !== slugify(source.value);
    target.addEventListener('input', () => { touched = target.value !== ''; });
    source.addEventListener('input', () => {
        if (!touched) target.value = slugify(source.value);
    });
});

// Especificaciones: agregar / quitar renglones
document.querySelectorAll('[data-specs]').forEach((box) => {
    const list = box.querySelector('[data-specs-list]');
    const template = box.querySelector('template');

    const renumber = () => {
        list.querySelectorAll('[data-spec-row]').forEach((row, i) => {
            row.querySelectorAll('input').forEach((input) => {
                input.name = input.name.replace(/specs\[\d+]/, `specs[${i}]`);
            });
        });
    };

    box.addEventListener('click', (event) => {
        if (event.target.closest('[data-spec-add]')) {
            list.append(template.content.cloneNode(true));
            renumber();
            list.lastElementChild?.querySelector('input')?.focus();
        }

        const remove = event.target.closest('[data-spec-remove]');
        if (remove) {
            remove.closest('[data-spec-row]').remove();
            renumber();
        }

        const move = event.target.closest('[data-spec-up]');
        if (move) {
            const row = move.closest('[data-spec-row]');
            row.previousElementSibling?.before(row);
            renumber();
        }
    });
});

// Confirmación antes de borrar
document.addEventListener('submit', (event) => {
    const message = event.target.dataset.confirm;
    if (message && !window.confirm(message)) event.preventDefault();
});

// Vista previa de imagen / video elegidos
document.querySelectorAll('[data-preview]').forEach((input) => {
    input.addEventListener('change', () => {
        const target = document.querySelector(input.dataset.preview);
        const file = input.files?.[0];
        if (!target || !file) return;
        target.src = URL.createObjectURL(file);
        target.hidden = false;
    });
});
