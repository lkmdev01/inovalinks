#!/bin/bash

# Este script deve ser executado após iniciar o contêiner Docker

# Cria o link simbólico se não existir
if [ ! -d "public/storage" ]; then
  ln -sf $(pwd)/storage/app/public public/storage
fi

# Configura permissões para o diretório de storage
chmod -R 777 storage/app/public
chmod -R 777 storage/app/public/avatars

# Configura permissões no contêiner
docker exec inovalink-app bash -c "
  mkdir -p /var/www/storage/app/public/avatars
  chmod -R 777 /var/www/storage/app/public
  chmod -R 777 /var/www/storage/app/public/avatars
  
  if [ ! -L \"/var/www/public/storage\" ]; then
    ln -sf ../storage/app/public /var/www/public/storage
  fi
  
  if [ -L \"/var/www/public/storage\" ]; then
    chmod -R 777 \$(readlink /var/www/public/storage)
  fi
  
  php artisan storage:link
"

echo "Configuração pós-build concluída!" 