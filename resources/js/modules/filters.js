// Filtros del listado: se aplican en el navegador, sin recargar.

const values = (form, name) =>
    [...form.querySelectorAll(`input[name="${name}"]:checked`)].map((input) => input.value);

function initRange(range, onChange) {
    const [from, to] = range.querySelectorAll('input[type="range"]');
    const output = range.querySelector('[data-range-output]');
    const min = Number(range.dataset.min);
    const max = Number(range.dataset.max);

    const paint = (event) => {
        // Que una manija no cruce a la otra
        if (Number(from.value) > Number(to.value)) {
            if (event?.target === from) from.value = to.value;
            else to.value = from.value;
        }

        const pct = (value) => ((Number(value) - min) / (max - min)) * 100;

        range.style.setProperty('--from', `${pct(from.value)}%`);
        range.style.setProperty('--to', `${pct(to.value)}%`);
        if (output) output.textContent = `${from.value} – ${to.value} ton`;

        // La manija que esté más a la derecha queda encima
        from.style.zIndex = Number(from.value) > max - 2 ? 3 : 1;
    };

    from.addEventListener('input', (event) => { paint(event); onChange(); });
    to.addEventListener('input', (event) => { paint(event); onChange(); });
    paint();

    return () => [Number(from.value), Number(to.value)];
}

export function initFilters() {
    const root = document.querySelector('[data-filters]');
    if (!root) return;

    const form = root.querySelector('[data-filters-form]');
    const items = [...root.querySelectorAll('[data-product]')];
    const count = document.querySelector('[data-filters-count]');
    const countLabel = document.querySelector('[data-filters-count-label]');
    const empty = root.querySelector('[data-filters-empty]');
    const range = root.querySelector('[data-range]');

    let getTons = () => [-Infinity, Infinity];

    const apply = () => {
        const machines = values(form, 'machine');
        const boxes = values(form, 'box');
        const [minTons, maxTons] = getTons();
        let visible = 0;

        items.forEach((item) => {
            const { machine, box, tonsMin, tonsMax } = item.dataset;
            const show =
                (!machines.length || machines.includes(machine)) &&
                (!boxes.length || boxes.includes(box)) &&
                Number(tonsMax) >= minTons &&
                Number(tonsMin) <= maxTons;

            item.hidden = !show;
            if (show) visible += 1;
        });

        if (count) count.textContent = visible;
        if (countLabel) countLabel.textContent = visible === 1 ? 'elemento' : 'elementos';
        if (empty) empty.hidden = visible > 0;
    };

    if (range) getTons = initRange(range, apply);
    form.addEventListener('change', apply);

    // Panel de filtros en móvil
    const openButton = document.querySelector('[data-filters-open]');
    const closeButton = root.querySelector('[data-filters-close]');
    let backdrop = null;

    const close = () => {
        document.body.classList.remove('is-filters-open');
        backdrop?.remove();
        backdrop = null;
    };

    openButton?.addEventListener('click', () => {
        document.body.classList.add('is-filters-open');
        backdrop = document.createElement('div');
        backdrop.className = 'filters-backdrop';
        backdrop.addEventListener('click', close);
        document.body.append(backdrop);
    });

    closeButton?.addEventListener('click', close);
    document.addEventListener('turbo:before-cache', close, { once: true });
}
