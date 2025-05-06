web: php artisan serve --host=0.0.0.0 --port=$PORT
   release: mkdir -p database && touch database/database.sqlite && php artisan migrate --force && php artisan optimize