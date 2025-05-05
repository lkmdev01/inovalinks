web: vendor/bin/heroku-php-apache2 public/
release: mkdir -p database && touch database/database.sqlite && php artisan migrate --force && php artisan optimize 