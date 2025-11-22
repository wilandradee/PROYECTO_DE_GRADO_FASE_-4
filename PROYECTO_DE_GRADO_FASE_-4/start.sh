#!/bin/bash
# Script rápido para iniciar servicios y dar instrucciones
echo "1) Construyendo contenedores..."
docker-compose build

echo "2) Levantando contenedores..."
docker-compose up -d

echo "3) Si es la primera vez: ingresar al contenedor y ejecutar migrations:"
echo "   docker exec -it bienestar_aplicacion bash"
echo "   cd /var/www/laravel_app"
echo "   cp .env.example .env"
echo "   composer install"
echo "   php artisan key:generate"
echo "   php artisan migrate --seed"
