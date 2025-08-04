# Makefile для проекта MMK Moon
# Laravel + Docker проект

.PHONY: help build up down restart logs clean install migrate seed test artisan

# Переменные
COMPOSE_FILE = docker-compose.yml
PROJECT_NAME = mmk-moon

# Основная команда помощи
help:
	@echo "Доступные команды:"
	@echo "  build     - Собрать Docker образы"
	@echo "  up        - Запустить все сервисы"
	@echo "  down      - Остановить все сервисы"
	@echo "  restart   - Перезапустить все сервисы"
	@echo "  logs      - Показать логи всех сервисов"
	@echo "  logs-f    - Показать логи с follow"
	@echo "  clean     - Остановить и удалить контейнеры, сети, тома"
	@echo "  install   - Установить зависимости Laravel"
	@echo "  migrate   - Запустить миграции"
	@echo "  seed      - Заполнить базу данных тестовыми данными"
	@echo "  test      - Запустить тесты"
	@echo "  artisan   - Выполнить команду artisan (использование: make artisan cmd='migrate')"
	@echo "  shell     - Войти в контейнер PHP"
	@echo "  db-shell  - Войти в контейнер PostgreSQL"
	@echo "  status    - Показать статус контейнеров"

# Сборка образов
build:
	docker-compose -f $(COMPOSE_FILE) build

# Запуск всех сервисов
up:
	docker-compose -f $(COMPOSE_FILE) up -d

# Остановка всех сервисов
down:
	docker-compose -f $(COMPOSE_FILE) down

# Перезапуск всех сервисов
restart:
	docker-compose -f $(COMPOSE_FILE) restart

# Показать логи
logs:
	docker-compose -f $(COMPOSE_FILE) logs

# Показать логи с follow
logs-f:
	docker-compose -f $(COMPOSE_FILE) logs -f

# Полная очистка (контейнеры, сети, тома)
clean:
	docker-compose -f $(COMPOSE_FILE) down -v --remove-orphans
	docker system prune -f

# Установка зависимостей Laravel
install:
	docker-compose -f $(COMPOSE_FILE) exec php composer install

# Запуск миграций
migrate:
	docker-compose -f $(COMPOSE_FILE) exec php php artisan migrate

# Откат миграций
migrate-rollback:
	docker-compose -f $(COMPOSE_FILE) exec php php artisan migrate:rollback

# Заполнение базы данных
seed:
	docker-compose -f $(COMPOSE_FILE) exec php php artisan db:seed

# Запуск тестов
test:
	docker-compose -f $(COMPOSE_FILE) exec php php artisan test

# Выполнение команды artisan
artisan:
	docker-compose -f $(COMPOSE_FILE) exec php php artisan $(cmd)

# Вход в контейнер PHP
shell:
	docker-compose -f $(COMPOSE_FILE) exec php bash

# Вход в контейнер PostgreSQL
db-shell:
	docker-compose -f $(COMPOSE_FILE) exec postgresql psql -U postgres

# Показать статус контейнеров
status:
	docker-compose -f $(COMPOSE_FILE) ps

# Очистка кэша Laravel
cache-clear:
	docker-compose -f $(COMPOSE_FILE) exec php php artisan cache:clear
	docker-compose -f $(COMPOSE_FILE) exec php php artisan config:clear
	docker-compose -f $(COMPOSE_FILE) exec php php artisan route:clear
	docker-compose -f $(COMPOSE_FILE) exec php php artisan view:clear

# Генерация ключа приложения
key-generate:
	docker-compose -f $(COMPOSE_FILE) exec php php artisan key:generate

# Создание символической ссылки для storage
storage-link:
	docker-compose -f $(COMPOSE_FILE) exec php php artisan storage:link

# Полная настройка проекта (первый запуск)
setup: build up install key-generate migrate seed storage-link
	@echo "Проект успешно настроен!"
	@echo "Доступные URL:"
	@echo "  - Веб-приложение: http://localhost"
	@echo "  - Adminer (БД): http://localhost:8080"
	@echo "  - API: http://localhost/api"
