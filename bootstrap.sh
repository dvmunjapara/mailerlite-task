docker exec -i mailerlite-app-fpm composer install
docker exec -i mailerlite-app-mysql mysql -umailerlite -pmailerlite mailerlite < mailerlite.sql
