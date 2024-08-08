include /.$(PWD)/.env

# colors
RED=\033[31m
GREEN=\033[32m
YELLOW=\033[33m
NO_COLOR=\033[0m

# targets
default:
	@echo "> make [targets]"
	@echo "${RED}Please specify a target. Check inside the ${YELLOW}./makefile${RED} for available targets.${NO_COLOR}"

docker-install: .env
	docker compose build
	docker compose up database -d
	docker compose run --rm backend make install
	@echo "${GREEN}Docker installation done!${NO_COLOR}"
	@echo "You can now run ${YELLOW}make docker-start${NO_COLOR}"

docker-start: .env
	docker compose up

docker-ngrok-backend: .env
	docker run -it -p 4040:4040 -e NGROK_AUTHTOKEN=$(NGROK_AUTHTOKEN) --name email-orders-backend-ngrok-temp --rm ngrok/ngrok:latest http host.docker.internal:$(APP_PORT)

docker-backend-shell:
	docker exec -it email-orders-backend /bin/bash

docker-frontend-shell:
	docker exec -it email-orders-frontend /bin/bash

docker-stop:
	docker compose down

docker-delete:
	docker compose rm -s -f -v database
	docker rmi mariadb
	docker compose rm -s -f -v backend
	docker rmi email-orders-backend
	docker compose rm -s -f -v frontend
	docker rmi email-orders-frontend
	docker compose rm -s -f -v maildev
	docker rmi maildev/maildev
	docker rmi ngrok/ngrok

delete-dependencies:
	rm -rf backend/vendor
	rm -rf frontend/node_modules
	rm -rf frontend/.next

delete-database-data:
	rm -rf .db-data

remove-all:
	make docker-delete
	make delete-dependencies
	make delete-database-data

reset-frontend:
	docker compose rm -s -f -v frontend
	docker rmi email-orders-frontend
	rm -rf frontend/node_modules
	rm -rf frontend/.next
	docker compose up -d

reset-all:
	make remove-all
	make docker-install
	make docker-start
