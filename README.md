# KODU — sitio web

Maqueta del ecommerce de KODU en Laravel 13 con Blade, SCSS, Bootstrap 5 y Vite.

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev        # desarrollo
npm run build      # producción
```

## Estructura

| Carpeta | Contenido |
| --- | --- |
| `config/kodu.php` | Textos del sitio, menú, contacto, redes y cobertura |
| `config/catalog.php` | Categorías, productos, etiquetas, filtros y fichas técnicas |
| `resources/views/layouts` | Layout base con etiquetas SEO |
| `resources/views/components` | `site` (header, footer, SEO), `ui`, `cards`, `sections`, `catalog`, `product`, `forms` |
| `resources/views/pages` | Inicio, listado, ficha de producto y contacto |
| `resources/scss` | `abstracts`, `vendor` (Bootstrap), `base`, `layout`, `components`, `pages` |
| `resources/js/modules` | Menú móvil, carrusel y filtros |
| `public/images` | Logo, favicon, imagen para redes y fotos de productos |

## Notas

- La navegación entre páginas usa Turbo Drive: cambia el contenido sin recargar la pantalla.
- Geist va en un solo archivo variable (`resources/fonts/geist-latin.woff2`) con todos los pesos.
- Si un producto no tiene imagen, o el archivo no existe, se muestra un rectángulo gris.
- `/sitemap.xml` y `/robots.txt` se generan solos. En producción `robots.txt` permite indexar; en otros entornos lo bloquea.
- El formulario de contacto valida y guarda el mensaje en el log; falta conectar el envío de correo.
