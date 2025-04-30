.PHONY: build up down restart

build:
	docker compose build --no-cache

dev:
	docker compose up --pull always -d --wait
	@echo ""
	@echo "🚀 Project is now running at: \033[1;34mhttps://localhost\033[0m"
	@echo ""

down:
	docker compose down --remove-orphans

front-up:
	@if [ "$$(docker inspect -f '{{.State.Health.Status}}' tomsholidays-php-1)" = "healthy" ]; then \
    		echo "✅ Container is healthy. Running Webpack Encore..."; \
    		npm run watch; \
    else \
    		echo "⚠️ Container is not healthy. Skipping launch frontend."; \
    fi


start: dev front-up

restart: down dev

data-load:
	php bin/console doctrine:fixtures:load
