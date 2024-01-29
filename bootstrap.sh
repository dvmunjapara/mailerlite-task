docker exec -i mailerlite-app-mysql mysql -umailerlite -pmailerlite mailerlite < mailerlite.sql
docker exec -i mailerlite-app-fpm composer install
