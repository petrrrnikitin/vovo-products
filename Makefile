.PHONY: up down build restart artisan migrate composer clear swagger

PHP = docker compose exec --user $(shell id -u):$(shell id -g) php
PHP_ROOT = docker compose exec php

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build

restart:
	docker compose restart

artisan:
	$(PHP) php artisan $(cmd)

migrate:
	$(PHP) php artisan migrate

composer:
	$(PHP_ROOT) composer $(cmd)

clear:
	$(PHP) php artisan route:clear
	$(PHP) php artisan config:clear
	$(PHP) php artisan cache:clear

swagger:
	$(PHP_ROOT) php artisan l5-swagger:generate
