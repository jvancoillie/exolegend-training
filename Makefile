# Setup ————————————————————————————————————————————————————————————————————————————————————————————————————————————————
PROJECT = exolegend-training
PWD = $(shell pwd)

IS_DOCKER = $(shell docker info > /dev/null 2>&1 && echo 1)

ifeq ($(IS_DOCKER), 1)
	DR = docker run -i -t --rm -v $(PWD):/srv/app  --name="$(PROJECT)" $(PROJECT)
	CONSOLE     = $(DR) bin/console
	COMPOSER    = $(DR) composer
	PHP         = $(DR) php
else
	CONSOLE     = bin/console
	COMPOSER    = composer
	PHP         = php
endif

.SILENT:
.DEFAULT_GOAL := help

## —— Make file ————————————————————————————————————————————————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z\-\_0-9\.@]+:.*?##.*$$)|(^##)' $(firstword  $(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##—— Tests ————————————————————————————————————————————————————————————————————————————————————————————————
tests: ## Run phpunit
	$(PHP) vendor/bin/phpunit

play: ## run index.php with referee.txt as input
	make referee
	$(PHP) public/index.php < public/referee.txt

.PHONY: tests play
## —— Build ———————————————————————————————————————————————————————————————————————————————————————————————
combine: ## combine code into combine.php file
	$(PHP) bin/build

referee: ## create referee.txt file based on referee.json
	$(PHP) bin/referee

##—— QA ————————————————————————————————————————————————————————————————————————————————————————————————
format: ## Format code with php-cs-fixer
	$(PHP) vendor/bin/php-cs-fixer fix
## —— Docker ———————————————————————————————————————————————————————————————————————————————————————————————
build: ## Build the container
	docker build --target php-cli -t $(PROJECT) .

sh: ## Run container
	 $(DR) /bin/sh

up: build sh ## Run sh in container

stop: ## Stop and remove a running container
	docker stop $(PROJECT); docker rm $(PROJECT)

local: ## run cg-local app
	java -jar ./cg-local-app-1.3.0.jar