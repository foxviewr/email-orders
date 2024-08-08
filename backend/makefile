# colors
RED=\033[31m
GREEN=\033[32m
YELLOW=\033[33m
NO_COLOR=\033[0m

# targets
default:
	@echo "> make [targets]"
	@echo "${RED}Please specify a target. Check inside the ${YELLOW}./makefile${RED} for available targets.${NO_COLOR}"

install:
	composer install --no-interaction
	php artisan migrate --force --no-interaction
	php artisan cache:clear --no-interaction
	php artisan optimize --no-interaction

start:
	php artisan serve --port=$(port) --host=0.0.0.0 --no-interaction
