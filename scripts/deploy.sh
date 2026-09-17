#!/bin/bash

set -euo pipefail

export PATH="/opt/plesk/node/24/bin:/opt/plesk/php/8.4/bin:$PATH"

cd "$(dirname "$(readlink -f "$0")")/.."

php artisan down --retry=15 || true

trap 'php artisan up' EXIT

composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

npm ci
npm run build

php artisan optimize:clear
php artisan filament:optimize-clear
php artisan migrate --force
php artisan storage:link || true
php artisan optimize
php artisan filament:optimize
