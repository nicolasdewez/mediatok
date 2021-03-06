SHELL = /bin/bash -o pipefail
.DEFAULT_GOAL := help

#################################
# Configuration
#################################

# Global
PROJECT ?= mediatok
APP = php
WEB = web
DB = db
DB_NAME = mediatok
RABBITMQ = rabbitmq
NETWORK = mediatok
DEBUG = $(debug)

# Aliases
COMPOSE = $(ENV_VARS) docker-compose -p $(PROJECT) -f docker-compose.yml
RUN = $(COMPOSE) run --rm --user=www-data $(APP)
EXEC = $(COMPOSE) exec -T --user=www-data
ENV_VARS = NETWORK=$(NETWORK) DEBUG=$(DEBUG)

# Project name must be compatible with docker-compose
override PROJECT := $(shell echo $(PROJECT) | tr -d -c '[a-z0-9]' | cut -c 1-55)

# Print output
# For colors, see https://en.wikipedia.org/wiki/ANSI_escape_code#Colors
INTERACTIVE := $(shell tput colors 2> /dev/null)
COLOR_UP = 3
COLOR_INSTALL = 6
COLOR_WAIT = 5
COLOR_STOP = 1
PRINT_CLASSIC = cat
PRINT_PRETTY = sed 's/^/$(shell printf "\033[3$(2)m[%-7s]\033[0m " $(1))/'
PRINT_PRETTY_NO_COLORS = sed 's/^/$(shell printf "[%-7s] " $(1))/'
PRINT = PRINT_CLASSIC


#################################
# Targets
#################################

.PHONY: start
start: pretty mkdir network up install ## Start containers & install application

.PHONY: up
up: ## Builds, (re)creates, starts containers
	@$(COMPOSE) up -d --remove-orphans 2>&1 | $(call $(PRINT),UP,$(COLOR_UP))

.PHONY: install
install: ready ## Install application
	@$(COMPOSE) exec $(DB) /usr/local/src/init.sh | $(call $(PRINT),INSTALL,$(COLOR_INSTALL))
	@$(RUN) bin/install | $(call $(PRINT),INSTALL,$(COLOR_INSTALL))

.PHONY: ready
ready: pretty ## Check if environment is ready
	@echo "[READY]" | $(call $(PRINT),READY,$(COLOR_READY))
	@docker run --rm --net=$(NETWORK) -e TIMEOUT=30 -e TARGETS=$(APP):9000 ddn0/wait 2> /dev/null
	@docker run --rm --net=$(NETWORK) -e TIMEOUT=30 -e TARGETS=$(WEB):80 ddn0/wait 2> /dev/null
	@docker run --rm --net=$(NETWORK) -e TIMEOUT=30 -e TARGETS=$(DB):5432 ddn0/wait 2> /dev/null
	@docker run --rm --net=$(NETWORK) -e TIMEOUT=30 -e TARGETS=$(RABBITMQ):5672 ddn0/wait 2> /dev/null

.PHONY: open
open: ## Open the browser
	@xdg-open http://$(WEB).$(NETWORK)/ > /dev/null

.PHONY: phpunit
phpunit: ## Run phpunit test suite
	@$(EXEC) $(APP) vendor/bin/phpunit

.PHONY: php-cs-fixer
php-cs-fixer: ## Run php-cs-fixer
	@$(RUN) vendor/bin/php-cs-fixer fix -v --dry-run --diff --config=.php_cs.dist

.PHONY: php-cs-fixer-exec
php-cs-fixer-exec: ## Run php-cs-fixer
	@$(RUN) vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.dist

.PHONY: run
run: ## Execute a command in a new application container (ie. make run cmd="ls -l")
ifndef cmd
	@echo "To use the 'run' target, you MUST add the 'cmd' argument"
	exit 1
endif
	@$(RUN) $(cmd)

.PHONY: exec
exec: ## Open a shell in the application container (options: user [www-data], cmd [bash], cont [`php`])
	$(eval cont ?= $(APP))
	$(eval user ?= www-data)
	$(eval cmd ?= bash)
	@$(COMPOSE) exec --user $(user) $(cont) $(cmd)

.PHONY: pgsql
pgsql: ## Run pgsql cli (options: db_name [`mediatok`])
	$(eval db_name ?= $(DB_NAME))
	@$(COMPOSE) exec $(DB) psql $(db_name) -U mediatok

.PHONY: migrate
migrate: ## Run doctrine migrations
	@$(RUN) bin/console doctrine:migrations:migrate --no-interaction

.PHONY: ps
ps: ## List containers status
	@$(COMPOSE) ps

.PHONY: logs
logs: ## Dump containers logs
	@$(COMPOSE) logs -f

.PHONY: stop
stop: ## Stop containers
	@$(COMPOSE) stop 2>&1 | $(call $(PRINT),STOP,$(COLOR_INSTALL))

.PHONY: rm
rm: ## Remove containers
	@$(COMPOSE) rm --all -f 2>&1 | $(call $(PRINT),REMOVE,$(COLOR_INSTALL))

.PHONY: down
.PHONY: down
down: ## Stop and remove containers, networks, volumes
	@$(COMPOSE) down -v --remove-orphans

.PHONY: destroy
destroy: stop rm ## Stop and remove containers

.PHONY: recreate
recreate: destroy up ## Recreate containers

.PHONY: clear
clear: ## Clear cache & logs
	rm -rf app/cache/* app/logs/*

.PHONY: reset
reset: down clear ## Reset application
	rm -rf vendor/ app/bootstrap.php.cache app/config/parameters.yml node_modules/

.PHONY: mkdir
mkdir:
	@mkdir -p ~/.composer

.PHONY: network
network:
	@docker network create ${NETWORK} 2> /dev/null || true

.PHONY: pretty
pretty:
ifdef INTERACTIVE
	$(eval PRINT = PRINT_PRETTY)
else
	$(eval PRINT = PRINT_PRETTY_NO_COLORS)
endif
	@true

.PHONY: help
help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'
