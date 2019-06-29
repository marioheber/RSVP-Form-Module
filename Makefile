include .env

.PHONY: up down stop prune ps shell drush logs restore

default: up

DRUPAL_ROOT ?= /var/www/html/web

up:
	@echo "Updating local settings for $(PROJECT_NAME)..."
	ln -f env/settings.local.php $(DRUPAL_ROOT)/sites/default/
	@echo "Starting up containers for $(PROJECT_NAME)..."
	docker-compose up -d --remove-orphans

down: stop

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose stop

clean:
	@echo "Removing containers for $(PROJECT_NAME)..."
	@docker-compose down -v

ps:mak
	@docker ps --filter name='$(PROJECT_NAME)*'

in:
	docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") sh

drush:
	docker exec $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) $(filter-out $@,$(MAKECMDGOALS))

logs:
	@docker-compose logs -f $(filter-out $@,$(MAKECMDGOALS))
