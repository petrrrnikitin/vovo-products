# VOVO Marketplace — Product Search API

REST API для поиска и фильтрации товаров маркетплейса.

## Стек

| Компонент | Версия |
|-----------|--------|
| PHP | 8.4 |
| Laravel | 13 |
| MariaDB | 11 |
| Elasticsearch | 8.13 |
| Redis | 7 |

## Запуск

### 1. Подготовка окружения

```bash
cp .env.example .env
```

### 2. Сборка и запуск контейнеров

```bash
make build
make up
```

### 3. Установка зависимостей и миграции

```bash
make composer cmd="install"
make artisan cmd="key:generate"
make migrate
make artisan cmd="db:seed"
```

### 4. Индексация товаров в Elasticsearch

```bash
make artisan cmd="products:reindex"
```

### 5. Генерация Swagger-документации

```bash
make swagger
```

Документация доступна по адресу: **http://localhost:8080/api/documentation**
