#!/bin/bash

# Este script deve ser executado dentro do contêiner Docker para corrigir permissões

# Garante que os diretórios existam
mkdir -p /var/www/storage/app/public/avatars

# Define permissões
chmod -R 777 /var/www/storage/app/public
chmod -R 777 /var/www/storage/app/public/avatars

# Recria o link simbólico, se necessário
rm -f /var/www/public/storage
ln -sf ../storage/app/public /var/www/public/storage
chmod -R 777 /var/www/public/storage

echo "Permissões de armazenamento corrigidas com sucesso!" 