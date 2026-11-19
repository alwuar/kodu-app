#!/usr/bin/env bash
# Se ejecuta EN EL SERVIDOR (cPanel) después de que GitHub Actions sube los archivos.
#   bash deploy/remote.sh        -> despliegue completo
#   bash deploy/remote.sh down   -> solo activa el modo mantenimiento
set -euo pipefail

cd "$(dirname "$0")/.."
APP_DIR="$(pwd)"

# PHP de cPanel: usa PHP_BIN si viene definido; si no, ea-php83; si no, el php del sistema.
if [ -n "${PHP_BIN:-}" ]; then
  PHP="$PHP_BIN"
elif [ -x /opt/cpanel/ea-php83/root/usr/bin/php ]; then
  PHP=/opt/cpanel/ea-php83/root/usr/bin/php
else
  PHP=php
fi
artisan() { "$PHP" "$APP_DIR/artisan" "$@"; }

if [ "${1:-}" = "down" ]; then
  if [ -f .env ] && [ -d vendor ]; then
    artisan down --retry=15 || true
  fi
  exit 0
fi

echo "==> $APP_DIR con $("$PHP" -r 'echo PHP_VERSION;')"

# Si algo falla, el sitio no se queda en mantenimiento (el job de Actions sí marca error).
trap 'echo "!! Falló el despliegue; sacando el sitio de mantenimiento"; artisan up || true' ERR

if [ ! -f .env ]; then
  echo "!! Falta $APP_DIR/.env — créalo antes del primer despliegue (ver deploy/README.md)."
  exit 1
fi

# storage/ no se sube con rsync: se asegura su estructura aquí.
mkdir -p storage/app/public storage/framework/{cache/data,sessions,testing,views} storage/logs bootstrap/cache
chmod -R u+rwX,g+rwX storage bootstrap/cache

if ! grep -q '^APP_KEY=base64:' .env; then
  artisan key:generate --force
fi

artisan migrate --force
artisan storage:link 2>/dev/null || true
artisan optimize:clear
artisan optimize
artisan queue:restart || true
artisan up

echo "==> Despliegue terminado"
