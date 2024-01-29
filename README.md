## MailerLite Task

### steps to run the project

- Clone the repository
- Run `cp .env.example .env`
- Run `cp mailerlite-vue/.env.example mailerlite-vue/.env`
- Run `docker-compose up -d`
- Run `chmod -x bootstrap.sh`
- Run `sh bootstrap.sh`
- Run `docker exec -i mailerlite-app-fpm ./vendor/bin/pest`
- Run `docker exec -i mailerlite-app-fpm ./vendor/bin/pest --coverage`
