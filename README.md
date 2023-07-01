# Symfony is RAD

This is the result of a RAD attempt in 30 mins.
Incomplete, but still impressive results based on Rick Kuipers talk
https://www.youtube.com/watch?v=Gj2F1GXp-i4

## Prerequisites

* Brew installed (See [brew.sh](/https://brew.sh))
* PHP installed (`brew install php`)
* Composer installed (`brew install composer`)
* Docker (optional, one could use local MySQL)

## Install Symfony-CLI

```shell
brew install symfony-cli/tap/symfony-cli
```

## All course commands/steps

```shell
symfony new chucklr --webapp
docker compose up -d
symfony server:start -d
symfony open:local
symfony console messenger:setup-transports
symfony console messenger:consume async
symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async
symfony server:log 
symfony server:status
```

Create a Makefile

```makefile
up:
	@make down
	docker compose up -d
	symfony server:start -d --no-tls
	sleep 20
	symfony console messenger:setup-transports
	symfony run -d symfony console messenger:consume async
	symfony open:local
	symfony server:log
down:
	docker compose down
	symfony server:stop
```

Run `make up`and `make down` to start and stop the environment.
Note, that I removed the original built-in symfony server and replaced it with docker php image (see below). So server:start and server:stop aren't needed any longer.

```shell
symfony console make:user
symfony console make:auth
symfony console make:registration-form
composer require symfonycasts/verify-email-bundle
composer require symfonycasts/reset-password-bundle
symfony console make:reset-password
symfony console make:migration
symfony console doctrine:migrations:migrate
symfony console make:entity
symfony console make:crud 
symfony console make:migration
symfony console doctrine:migrations:migrate

symfony composer require encore
nvm install --lts
```

## Taking it further

### Use Mysql instead of PostgreSQL

Replace postgres part in docker-compose.yml with:

```yaml
database:
    image: 'bitnami/mysql:latest'
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: app
      MYSQL_USER: app
      MYSQL_PASSWORD: app
      MYSQL_DATABASE: app
    ports:
      - '33060:3306'
    volumes:
      - database_data:/var/lib/mysql:rw
      ...
volumes:
###> doctrine/doctrine-bundle ###
  db-data:
```

Change .env

```ini
DATABASE_URL="mysql://root:password@127.0.0.1:3306/main?serverVersion=8.0.32&charset=utf8mb4"
```

For this change to take effect, do:

```shell
docker-compose down --rmi all
docker system prune
docker-compose up -d --build
```

### Making it all Docker

By adding PHP and Nginx, you got everything dockerized.

```yaml
php:
    image: php:8.2-fpm
    build: ./docker/php
    depends_on:
      - database
    volumes:
      - .:/var/www/html
      - ./docker/php/override.ini:/usr/local/etc/php/conf.d/override.ini

  nginx:
    image: nginx:latest
    build: ./docker/nginx
    volumes:
      - .:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    ports:
      - '8080:80'
    links:
      - php
```

Problem solving

```shell
docker system prune
docker-compose down --rmi all --volumes --remove-orphans
```
