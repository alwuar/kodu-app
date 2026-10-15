// Carrito: al escribir una cantidad se actualiza sola (sin botón extra).

export function initCart() {
    document.addEventListener('change', (event) => {
        const input = event.target.closest('[data-cart-qty] .qty-input');
        if (!input) return;

        const value = Math.max(0, Math.min(Number(input.max) || 99, Math.round(Number(input.value) || 0)));
        input.value = value;
        input.form.requestSubmit();
    });
}
