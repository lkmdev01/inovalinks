#!/bin/bash

# Este script deve ser executado dentro do contêiner para corrigir o link simbólico

# Remove o link simbólico existente
rm -f /var/www/public/storage

# Cria o link simbólico correto para o diretório local
ln -sf /var/www/storage/app/public /var/www/public/storage

# Garante permissões corretas
chmod -R 777 /var/www/storage/app/public
chmod -R 777 /var/www/storage/app/public/avatars

echo "Link simbólico corrigido e permissões ajustadas!" 