ENV_FILE ?= .env.docker
ENV_ARG = $(shell test -f $(ENV_FILE) && printf -- '--env-file %s' $(ENV_FILE))
APP_UID ?= $(shell id -u 2>/dev/null || echo 1000)
APP_GID ?= $(shell id -g 2>/dev/null || echo 1000)

DOCKER_COMPOSE = APP_UID=$(APP_UID) APP_GID=$(APP_GID) docker compose $(ENV_ARG)
DOCKER_COMPOSE_PROD = docker compose $(ENV_ARG) -f compose.yml

PHP_CONTAINER = app
POSTGRES_CONTAINER = postgres
VITE_CONTAINER = vite
VITE_EXEC = $(DOCKER_COMPOSE) exec -u $(APP_UID):$(APP_GID) $(VITE_CONTAINER)

APP_URL = http://localhost:8080
VITE_URL = http://localhost:5173
TEST_DB_DATABASE ?= runtracker_testing

BACKUP_DIR = backups
BACKUP_FILE ?= $(BACKUP_DIR)/runtracker-$(shell date +%Y%m%d-%H%M%S).dump
CMD_ARGS = $(or $(cmd),$(a),$(ARGS))

.PHONY: help build up down restart logs ps shell sh composer artisan install app-key migrate fresh seed db cache-clear config-clear route-clear view-clear optimize test-db test lint lint-fix phpstan deptrac qa vite-install vite-build vite-dev prod-build prod-up prod-down prod-restart prod-logs prod-ps prod-shell prod-db-backup prod-db-restore

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-22s\033[0m %s\n", $$1, $$2}'

build: ## Build development Docker images
	$(DOCKER_COMPOSE) build

up: ## Build and start development containers in background
	$(DOCKER_COMPOSE) up -d --build
	@echo ""
	@echo "  Application: $(APP_URL)"
	@echo "  Vite:        $(VITE_URL)"
	@echo ""

down: ## Stop development containers
	$(DOCKER_COMPOSE) down

restart: down up ## Restart development containers

logs: ## Follow development container logs
	$(DOCKER_COMPOSE) logs -f

ps: ## Show development containers
	$(DOCKER_COMPOSE) ps

shell: ## Open a shell in the PHP container
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) bash

sh: ## Open an sh shell in the PHP container
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) sh

composer: ## Run composer, e.g. make composer cmd=install
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) composer $(CMD_ARGS)

artisan: ## Run artisan, e.g. make artisan cmd=migrate
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan $(CMD_ARGS)

install: ## Install PHP and frontend dependencies
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) composer install
	$(MAKE) app-key
	$(VITE_EXEC) pnpm install

app-key: ## Generate APP_KEY in the local Docker env file if missing
	@test -f "$(ENV_FILE)" || (echo "Missing $(ENV_FILE). Copy .env.docker.example first." && exit 1)
	@if grep -Eq '^APP_KEY=base64:.+' "$(ENV_FILE)"; then \
		echo "APP_KEY already exists in $(ENV_FILE)"; \
	else \
		key="$$( $(DOCKER_COMPOSE) exec -T $(PHP_CONTAINER) php artisan key:generate --show --no-interaction )"; \
		case "$$key" in base64:*) ;; *) echo "Failed to generate APP_KEY" >&2; exit 1 ;; esac; \
		if grep -q '^APP_KEY=' "$(ENV_FILE)"; then \
			sed -i "s|^APP_KEY=.*|APP_KEY=$$key|" "$(ENV_FILE)"; \
		else \
			printf '\nAPP_KEY=%s\n' "$$key" >> "$(ENV_FILE)"; \
		fi; \
		echo "Generated APP_KEY in $(ENV_FILE)"; \
		$(DOCKER_COMPOSE) up -d --force-recreate --no-deps $(PHP_CONTAINER); \
	fi

migrate: ## Run database migrations
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan migrate

fresh: ## Recreate database schema and run seeders
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan migrate:fresh --seed

seed: ## Run database seeders
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan db:seed

db: ## Open psql in the Postgres container
	$(DOCKER_COMPOSE) exec $(POSTGRES_CONTAINER) psql -U "$${DB_USERNAME:-runtracker}" -d "$${DB_DATABASE:-runtracker}"

cache-clear: ## Clear Laravel cache
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan cache:clear

config-clear: ## Clear Laravel config cache
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan config:clear

route-clear: ## Clear Laravel route cache
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan route:clear

view-clear: ## Clear Laravel compiled views
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan view:clear

optimize: ## Cache Laravel config, routes, events, and views
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan optimize

test-db: ## Create testing PostgreSQL database if it does not exist
	$(DOCKER_COMPOSE) exec $(POSTGRES_CONTAINER) sh -c 'createdb -U "$${POSTGRES_USER:-runtracker}" "$(TEST_DB_DATABASE)" 2>/dev/null || psql -U "$${POSTGRES_USER:-runtracker}" -d postgres -tc "SELECT 1 FROM pg_database WHERE datname = '\''$(TEST_DB_DATABASE)'\''" | grep -q 1'

test: ## Run application tests, e.g. make test cmd=tests/Feature/HealthCheckTest.php
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php artisan test $(CMD_ARGS)

lint: ## Run Laravel Pint checks
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) ./vendor/bin/pint --test

lint-fix: ## Fix code style with Laravel Pint
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) ./vendor/bin/pint

phpstan: ## Run PHPStan if installed
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) ./vendor/bin/phpstan analyse

deptrac: ## Run Deptrac architecture checks
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) ./vendor/bin/deptrac analyse --no-progress

qa: lint phpstan deptrac test ## Run quality checks

vite-install: ## Install frontend dependencies
	$(VITE_EXEC) pnpm install

vite-build: ## Build frontend assets
	$(VITE_EXEC) pnpm build

vite-dev: ## Run Vite dev server in the foreground
	$(VITE_EXEC) pnpm dev --host 0.0.0.0

prod-build: ## Build production Docker images
	$(DOCKER_COMPOSE_PROD) build

prod-up: ## Build and start production containers in background
	$(DOCKER_COMPOSE_PROD) up -d --build

prod-down: ## Stop production containers
	$(DOCKER_COMPOSE_PROD) down

prod-restart: prod-down prod-up ## Restart production containers

prod-logs: ## Follow production container logs
	$(DOCKER_COMPOSE_PROD) logs -f

prod-ps: ## Show production containers
	$(DOCKER_COMPOSE_PROD) ps

prod-shell: ## Open an sh shell in the production PHP container
	$(DOCKER_COMPOSE_PROD) exec $(PHP_CONTAINER) sh

prod-db-backup: ## Create a compressed Postgres backup, override BACKUP_FILE if needed
	@mkdir -p $(BACKUP_DIR)
	$(DOCKER_COMPOSE_PROD) exec -T $(POSTGRES_CONTAINER) pg_dump -U "$${DB_USERNAME:-runtracker}" -d "$${DB_DATABASE:-runtracker}" -Fc > $(BACKUP_FILE)
	@echo "Backup written to $(BACKUP_FILE)"

prod-db-restore: ## Restore a compressed Postgres backup, e.g. make prod-db-restore BACKUP_FILE=backups/file.dump
	@test -f "$(BACKUP_FILE)" || (echo "Backup file not found: $(BACKUP_FILE)" && exit 1)
	$(DOCKER_COMPOSE_PROD) exec -T $(POSTGRES_CONTAINER) pg_restore --clean --if-exists --no-owner -U "$${DB_USERNAME:-runtracker}" -d "$${DB_DATABASE:-runtracker}" < $(BACKUP_FILE)
