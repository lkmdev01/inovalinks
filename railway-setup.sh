#!/bin/bash

# Script para configurar o ambiente no Railway

# Criar diretórios necessários
mkdir -p storage/app/public/avatars
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p bootstrap/cache

# Configurar as permissões
chmod -R 777 storage
chmod -R 777 bootstrap/cache
chmod -R 777 storage/app/public
chmod -R 777 storage/app/public/avatars
chmod -R 777 database

# Executar comandos do Laravel
php artisan key:generate --force
php artisan storage:link --force
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Configuração do Railway concluída!" 