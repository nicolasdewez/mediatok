.DEFAULT_GOAL := help

#################################
# Configuration
#################################

PROJECT = mediatok
PHP = php
WEB = web
DB = db
RABBITMQ = rabbitmq
DB_NAME = mediatok

DOCKER = docker
DOCKER_BUILD = $(DOCKER) build -t

NETWORK = mediatok
DEBUG = $(debug)

COMPOSE = docker-compose -p $(PROJECT) $(CONFIG)
RUN = $(COMPOSE) run --rm -e DEBUG=$(DEBUG)

# Print output
# For colors, see https://en.wikipedia.org/wiki/ANSI_escape_code#Colors
INTERACTIVE := $(shell tput colors 2> /dev/null)
COLOR_UP = 3
COLOR_INSTALL = 6
COLOR_READY = 5
COLOR_STOP = 1
PRINT_CLASSIC = cat
PRINT_PRETTY = sed 's/^/$(shell printf "\033[3$(2)m[%-7s]\033[0m " $(1))/'
PRINT_PRETTY_NO_COLORS = sed 's/^/$(shell printf "[%-7s] " $(1))/'
PRINT = PRINT_CLASSIC

#################################
# Profiles
#################################

CONFIG_COMPLETE = -f docker-compose.yml

# Default
CONFIG = $(CONFIG_COMPLETE)

.PHONY: complete
complete: ## Use "complete" profile
	$(eval CONFIG = $(CONFIG_COMPLETE))
	@true

#################################
# Targets
#################################
.PHONY: build
build: ## Prepare containers
	@$(COMPOSE) build

.PHONY: clear
clear: ## Clear cache & logs
	@$(RUN) $(PHP) rm -rf var/cache/* var/logs/*

.PHONY: destroy
destroy: stop ## Stop and remove containers
	@$(COMPOSE) rm --all -f $(APP) 2>&1 | $(call $(PRINT),REMOVE,$(COLOR_INSTALL))

.PHONY: down
down: ## Stop and remove containers, networks, volumes
	@$(COMPOSE) down -v --remove-orphans

.PHONY: exec
exec: ## Open a shell in the container (options: user=www-data, cmd=bash, cont=php)
	$(eval cont ?= $(PHP))
	$(eval user ?= www-data)
	$(eval cmd ?= bash)
	@$(COMPOSE) exec --user $(user) $(cont) $(cmd)

.PHONY: install
install: ready ## Install application
	@$(COMPOSE) exec $(DB) /usr/local/src/init.sh | $(call $(PRINT),INSTALL,$(COLOR_INSTALL))
	@$(COMPOSE) exec --user www-data $(PHP) bin/install | $(call $(PRINT),INSTALL,$(COLOR_INSTALL))

.PHONY: logs
logs: ## Dump containers logs (option: cont=php])
	@$(COMPOSE) logs -f $(cont)

.PHONY: network
network:
	@$(DOCKER) network create $(NETWORK) 2> /dev/null || true

.PHONY: pgsql
pgsql: ## Run pgsql cli
	@$(COMPOSE) exec $(DB) psql $(DB_NAME) -U mediatok

.PHONY: php-cs-fixer
php-cs-fixer: ## Run php-cs-fixer (options: fix=true)
ifndef fix
	@$(COMPOSE) exec --user www-data $(PHP) vendor/bin/php-cs-fixer fix -v --dry-run --diff --config=.php_cs.dist
	exit 0
endif
	@$(COMPOSE) exec --user www-data $(PHP) vendor/bin/php-cs-fixer fix -v --diff --config=.php_cs.dist

.PHONY: ps
ps: ## List containers status
	@$(COMPOSE) ps

.PHONY: ready
ready: pretty ## Check if environment is ready
	@echo "[READY]" | $(call $(PRINT),READY,$(COLOR_READY))
	@docker run --rm --net=$(NETWORK) -e TIMEOUT=30 -e TARGETS=$(PHP):9000 ddn0/wait 2> /dev/null
	@docker run --rm --net=$(NETWORK) -e TIMEOUT=30 -e TARGETS=$(WEB):80 ddn0/wait 2> /dev/null
	@docker run --rm --net=$(NETWORK) -e TIMEOUT=30 -e TARGETS=$(DB):5432 ddn0/wait 2> /dev/null
	@docker run --rm --net=$(NETWORK) -e TIMEOUT=30 -e TARGETS=$(RABBITMQ):5672 ddn0/wait 2> /dev/null

.PHONY: recreate
recreate: destroy up ## Recreate containers

.PHONY: reset
reset: clear destroy ## Reset application
	rm -rf vendor/ var/bootstrap.php.cache app/config/parameters.yml

.PHONY: run
run: ## Execute a command in a container (options: user=www-data, cont=php, cmd="pwd")
	$(eval user ?= www-data)
	$(eval cont ?= $(PHP))
ifndef cmd
	@echo "To use the 'run' target, you MUST add the 'cmd' argument"
	exit 1
endif
	@$(RUN) --user $(user) $(cont) $(cmd)

.PHONY: start
start: pretty network up install ## Start containers & install application

.PHONY: stop
stop: pretty ## Stop containers
	@$(COMPOSE) stop $(APP) 2>&1 | $(call $(PRINT),STOP,$(COLOR_INSTALL))

.PHONY: up
up: ## Builds, (re)creates, starts containers
	@$(COMPOSE) up -d --remove-orphans $(APP) 2>&1 | $(call $(PRINT),UP,$(COLOR_UP))

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
