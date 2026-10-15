# KODU — sitio web y tienda

Laravel 13 con Blade, SCSS, Bootstrap 5, Vite, Turbo y Stripe Checkout.

## Instalación

```bash
composer install
cp .env.example .env          # llena ADMIN_*, KODU_NOTIFY_EMAIL, STRIPE_* y MAIL_*
php artisan key:generate
php artisan migrate --seed    # crea tablas, catálogo inicial y el usuario del panel
php artisan storage:link      # para imágenes, PDF y video subidos desde el panel
npm install
npm run build                 # o npm run dev
```

El usuario del panel se toma de `ADMIN_EMAIL` y `ADMIN_PASSWORD`. Si `ADMIN_PASSWORD` está vacío,
`db:seed` genera una contraseña y la muestra en la terminal.

## Panel de administración (`/admin`)

- **Productos**: nombre, precio (MXN, IVA incluido), imagen, ficha técnica en PDF, tabla de especificaciones,
  maquinaria / tonelaje / tipo de caja (arman las etiquetas y los filtros) y visibilidad.
- **Categorías**: crear, editar, ordenar, ocultar y mostrar en el menú. La primera es la del carrusel del inicio.
- **Pedidos**: los que llegan pagados desde Stripe, con cliente, dirección y estado.
- **Distribuidores** y **Mensajes**: solicitudes y contactos recibidos desde el sitio.
- **Banner del inicio**: subir o quitar el video de fondo (MP4/WebM) y su imagen de carga.

## Stripe

1. En https://dashboard.stripe.com/apikeys copia la clave secreta a `STRIPE_SECRET` (y la publicable a `STRIPE_KEY`).
2. En Developers → Webhooks agrega el endpoint `https://TU-DOMINIO/stripe/webhook` con los eventos
   `checkout.session.completed`, `checkout.session.async_payment_succeeded`,
   `checkout.session.async_payment_failed` y `checkout.session.expired`. Copia el *signing secret* a `STRIPE_WEBHOOK_SECRET`.
3. En local: `stripe listen --forward-to localhost:8000/stripe/webhook` y usa el `whsec_…` que imprime.
4. Los métodos de pago (tarjeta, OXXO, etc.) se activan en Settings → Payment methods de Stripe.

Tarjeta de prueba: `4242 4242 4242 4242`, cualquier fecha futura y CVC.

## Correos

Los avisos de pedidos pagados, distribuidores y contacto llegan a `KODU_NOTIFY_EMAIL`; el cliente recibe
su confirmación de compra. Configura `MAIL_MAILER` (SMTP, Resend, Mailgun…); con `log` solo se escriben en `storage/logs`.

## Estructura

| Carpeta | Contenido |
| --- | --- |
| `config/kodu.php` | Textos del sitio, contacto, redes, cobertura y contenido de la página de distribuidores |
| `config/catalog.php` | Opciones fijas de los filtros (maquinaria, tipo de caja, rango de tonelaje) |
| `app/Models` | Category, Product, Order, OrderItem, DealerRequest, ContactMessage, Setting |
| `app/Services` | `Stripe` (API de Checkout y firma de webhooks) y `OrderPayments` |
| `app/Http/Controllers/Admin` | Panel de administración |
| `resources/views/components` | `site`, `ui`, `cards`, `sections`, `catalog`, `product`, `forms`, `admin`, `mail` |
| `resources/scss` | `abstracts`, `vendor`, `base`, `layout`, `components`, `pages` + `admin.scss` |
| `resources/js/modules` | Menú, carrusel, filtros, carrito y formulario emergente |

## Notas

- La navegación usa Turbo Drive: cambia de página sin recargar la pantalla.
- El sitio público y el panel cargan CSS y JS por separado; el panel no pesa en el sitio.
- Si un producto no tiene imagen se muestra un rectángulo gris.
- `/sitemap.xml` y `/robots.txt` se generan solos; en producción `robots.txt` permite indexar.
