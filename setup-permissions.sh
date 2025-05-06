#!/bin/bash

# Cria diretórios necessários
mkdir -p storage/app/public/avatars
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p bootstrap/cache

# Define permissões com mais permissões para o diretório de avatares
chmod -R 777 storage/app/public
chmod -R 777 storage/app/public/avatars
chmod -R 775 storage/framework
chmod -R 775 bootstrap/cache

# Se o link simbólico não existir, cria
if [ ! -d "public/storage" ]; then
  ln -sf ../storage/app/public public/storage
fi

# Garante que o link simbólico tenha as permissões corretas
chmod -R 777 public/storage

echo "Permissões configuradas com sucesso!" 