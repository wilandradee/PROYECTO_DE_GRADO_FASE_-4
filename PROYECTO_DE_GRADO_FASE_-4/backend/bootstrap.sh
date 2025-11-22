#!/bin/bash
set -e

echo "Bootstrap script: creating Laravel project inside ./backend/laravel_app (if not exists)..."

if [ ! -d "./backend/laravel_app" ]; then
  echo "Running: composer create-project laravel/laravel backend/laravel_app"
  composer create-project laravel/laravel backend/laravel_app
else
  echo "Laravel app folder already exists (backend/laravel_app). Skipping create-project."
fi

echo "Copying scaffold files into laravel app..."
# Copy scaffolded app files (Models, Controllers, migrations, routes) into laravel app
cp -r backend_scaffold/app/* backend/laravel_app/app/ || true
cp -r backend_scaffold/database/* backend/laravel_app/database/ || true
cp -r backend_scaffold/routes/* backend/laravel_app/routes/ || true
cp -r backend_scaffold/config/* backend/laravel_app/config/ || true

echo "Bootstrap completed. Now configure .env in backend/laravel_app and run migrations:"
echo "  cd backend/laravel_app"
echo "  cp .env.example .env"
echo "  # edit DB settings to match docker-compose (DB host: base_datos)"
echo "  php artisan key:generate"
echo "  php artisan migrate --seed"
