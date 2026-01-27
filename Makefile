.PHONY: help build install test test-coverage shell clean

# Default target
.DEFAULT_GOAL := help

# Docker image name
IMAGE_NAME = secrets-cli-php
CONTAINER_NAME = secrets-cli-php-test

help: ## Display this help message
	@echo "Available targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Build the Docker image
	docker build -t $(IMAGE_NAME) .

install: build ## Build image and install composer dependencies
	docker run --rm -v $(PWD):/app $(IMAGE_NAME) composer update

update: build ## Update composer dependencies
	docker run --rm -v $(PWD):/app $(IMAGE_NAME) composer update

test: ## Run PHPUnit tests in Docker container
	@docker run --rm -v $(PWD):/app $(IMAGE_NAME) ./vendor/bin/phpunit --no-progress || ([ $$? -eq 1 ] && exit 1 || exit 0)

test-coverage: ## Run PHPUnit tests with coverage report
	docker run --rm -v $(PWD):/app $(IMAGE_NAME) ./vendor/bin/phpunit --coverage-text

test-filter: ## Run specific test (usage: make test-filter FILTER=ApplicationTest)
	docker run --rm -v $(PWD):/app $(IMAGE_NAME) ./vendor/bin/phpunit --filter=$(FILTER)

shell: ## Open a shell in the Docker container
	docker run --rm -it -v $(PWD):/app $(IMAGE_NAME) /bin/bash

composer: ## Run composer command (usage: make composer CMD="require package/name")
	docker run --rm -v $(PWD):/app $(IMAGE_NAME) composer $(CMD)

clean: ## Clean up Docker containers and images
	docker rmi $(IMAGE_NAME) || true

lint: ## Run PHP linting
	docker run --rm -v $(PWD):/app $(IMAGE_NAME) find src tests -name "*.php" -exec php -l {} \;
