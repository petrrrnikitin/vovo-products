.PHONY: up down build restart artisan migrate composer

PHP = docker compose exec --user $(shell id -u):$(shell id -g) php

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
	$(PHP) composer $(cmd)
