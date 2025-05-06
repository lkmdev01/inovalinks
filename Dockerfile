FROM php:8.4-cli

# Argumentos para configuração de usuário
ARG user=laravel
ARG uid=1000

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  libzip-dev \
  libsqlite3-dev \
  zip \
  unzip \
  nodejs \
  npm

# Limpa cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd zip

# Obtém o Composer mais recente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cria usuário para executar os comandos
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
  chown -R $user:$user /home/$user

# Define o diretório de trabalho
WORKDIR /var/www

# Copia os arquivos do projeto
COPY . /var/www/

# Configura permissões apropriadas
RUN mkdir -p /var/www/storage/logs
RUN touch /var/www/storage/logs/laravel.log
RUN mkdir -p /var/www/database
RUN touch /var/www/database/database.sqlite
RUN chmod 777 /var/www/database/database.sqlite
RUN mkdir -p /var/www/storage/app/public/avatars
RUN chown -R $user:www-data /var/www
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database /var/www/public
# Garantindo que os diretórios de uploads tenham permissões especiais
RUN chmod -R 777 /var/www/storage/app/public
RUN chmod -R 777 /var/www/storage/app/public/avatars
RUN chmod -R 777 /var/www/bootstrap/cache

# Executa comandos de instalação e otimização
RUN composer install --optimize-autoloader --no-dev

# Instala dependências do NPM e compila os assets
RUN npm install
RUN npm run build

# Cria link simbólico do storage para os uploads
RUN php -r "file_exists('/var/www/public/storage') || symlink('/var/www/storage/app/public', '/var/www/public/storage');"
# Não modificamos as permissões do symlink aqui, deixamos para o script post-build

# Muda para o usuário não-root
USER $user

# Expõe a porta 8000
EXPOSE 8000

# Inicia o servidor
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"] 