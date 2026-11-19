# Despliegue automático a cPanel (GitHub Actions)

Cada **push a `main`** compila el proyecto en GitHub (composer sin dev + Vite), lo sube por SSH/rsync al cPanel de **producción** y ejecuta `deploy/remote.sh` (migraciones, caché, `storage:link`, salir de mantenimiento).

Para desplegar a **staging** (kodu.agenciavandu.com) o repetir un despliegue: *Actions → Deploy → Run workflow* y eliges el entorno.

El servidor **no necesita** git, composer ni node: solo SSH, rsync y PHP 8.3.

## Qué nunca se toca en el servidor

`.env`, `storage/` (logs, sesiones, archivos subidos), `public/storage` y `bootstrap/cache/*.php`.

---

## Configuración (una vez por servidor)

### 1. Llave SSH para el despliegue

En tu computadora:

```bash
ssh-keygen -t ed25519 -C "deploy-kodu-produccion" -f ~/.ssh/kodu_prod -N ""
```

En el cPanel del cliente: **Seguridad → Acceso SSH → Administrar llaves SSH → Importar llave**, pega el contenido de `~/.ssh/kodu_prod.pub` y después dale **Autorizar**.
(o en la Terminal de cPanel: `echo "CONTENIDO_DE_LA_.pub" >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys`)

Prueba desde tu computadora: `ssh -i ~/.ssh/kodu_prod -p PUERTO usuario@servidor` (HostGator suele usar el puerto **2222**).

### 2. Base de datos

cPanel → **Bases de datos MySQL**: crea la base, el usuario y asígnale todos los privilegios.

### 3. Carpeta de la app y `.env` (Terminal de cPanel)

```bash
mkdir -p ~/kodu
nano ~/kodu/.env
```

Contenido mínimo de producción:

```dotenv
APP_NAME="Kodu"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://dominio-del-cliente.com

APP_LOCALE=es
APP_FALLBACK_LOCALE=es

LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=usuario_kodu
DB_USERNAME=usuario_kodu
DB_PASSWORD=********

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

MAIL_MAILER=smtp
MAIL_HOST=mail.dominio-del-cliente.com
MAIL_PORT=465
MAIL_USERNAME=no-reply@dominio-del-cliente.com
MAIL_PASSWORD=********
MAIL_FROM_ADDRESS=no-reply@dominio-del-cliente.com
MAIL_FROM_NAME="${APP_NAME}"
```

`APP_KEY` vacío está bien: el primer despliegue la genera.

### 4. Apuntar el dominio principal a `public/`

El dominio principal de cPanel siempre sirve `~/public_html`. Se reemplaza por un enlace a la carpeta `public` de Laravel:

```bash
cd ~
mv public_html public_html_respaldo     # revisa antes que no haya nada del cliente que necesites
ln -s ~/kodu/public public_html
```

(Para un subdominio o dominio adicional basta con poner su raíz en `kodu/public` desde **Dominios**.)

### 5. Cron del scheduler (opcional, si la app usa tareas programadas o colas)

cPanel → **Trabajos de Cron**, cada minuto:

```
* * * * * /opt/cpanel/ea-php83/root/usr/bin/php ~/kodu/artisan schedule:run >> /dev/null 2>&1
```

### 6. Secrets en GitHub

Repo → **Settings → Environments** → crea `deploy` (y `staging` para kodu.agenciavandu.com). En cada uno:

**Secrets**

| Nombre | Ejemplo |
|---|---|
| `SSH_HOST` | `dominio-del-cliente.com` o la IP del servidor |
| `SSH_PORT` | `2222` |
| `SSH_USER` | usuario de cPanel |
| `SSH_KEY` | contenido completo de `~/.ssh/kodu_prod` (la llave **privada**) |
| `DEPLOY_PATH` | `/home/USUARIO/kodu` (ruta absoluta de una **subcarpeta**; nunca el home `/home/USUARIO`. El workflow se niega a desplegar si apunta al home o a una carpeta con otros archivos) |

**Variables** (opcionales)

| Nombre | Para qué |
|---|---|
| `APP_URL` | Al final se comprueba que el sitio responda sin error 500 |
| `PHP_BIN` | Solo si el PHP 8.3 no está en `/opt/cpanel/ea-php83/root/usr/bin/php` |

Opcional en `deploy`: **Required reviewers** para aprobar cada despliegue a producción con un clic.

### 7. Primer despliegue

*Actions → Deploy → Run workflow → production*. Si el log termina en `Despliegue terminado`, listo. A partir de ahí cada push a `main` publica solo.

---

## Si algo falla

- **Permission denied (publickey):** la llave no está autorizada en cPanel o `SSH_USER`/`SSH_PORT` no coinciden.
- **Falta .env:** paso 3.
- **Error de migración:** el sitio vuelve a línea automáticamente y el job queda en rojo con el error; corrige y vuelve a hacer push.
- **Error 500 después de desplegar:** revisa `~/kodu/storage/logs/` en el servidor.
