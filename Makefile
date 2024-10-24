.PHONY: help install start stop restart fixtures clean

PORT ?= 8000

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install:
	composer install
	php bin/console doctrine:database:create --if-not-exists
	php bin/console doctrine:migrations:migrate --no-interaction
	php bin/console cache:clear

install-docker:
	docker compose up -d
	docker compose exec php composer install
	docker compose exec php php bin/console doctrine:database:create --if-not-exists
	docker compose exec -u root php chmod 666 var/data.db
	docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
	docker compose exec php php bin/console cache:clear

start:
	@if command -v symfony >/dev/null 2>&1; then \
		symfony serve -d --port=$(PORT); \
	else \
		php -S localhost:$(PORT) -t public/; \
	fi

start-docker:
	docker compose up -d

stop:
	symfony server:stop

stop-docker:
	docker compose down

restart: stop start

restart-docker: stop-docker start-docker

fixtures:
	php bin/console doctrine:fixtures:load --no-interaction

fixtures-docker:
	docker compose exec php php bin/console doctrine:fixtures:load --no-interaction

clean:
	php bin/console cache:clear
	rm -rf var/cache/*
	rm -rf var/log/*