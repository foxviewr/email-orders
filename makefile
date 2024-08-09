include ./.env
include ./makelib/ansi-sgr.c
include ./make-info.c

# targets
default:
	@$(call make_info)

docker-install: .env
	@docker compose build
	@docker compose up database -d
	@docker compose run --rm backend make install
	@echo "${GREEN}Docker installation done!${NORMAL}"
	@echo "You can now run ${YELLOW}make docker-start${NORMAL}"

docker-start: .env
	@docker compose up

docker-maildev: .env
	@docker run -it \
		-p $(MAILDEV_WEB_PORT):$(MAILDEV_WEB_PORT) \
		-p $(MAILDEV_SMTP_PORT):$(MAILDEV_SMTP_PORT) \
		-e TZ=$(MAILDEV_TIMEZONE) \
		-e MAILDEV_WEB_PORT=$(MAILDEV_WEB_PORT) \
		-e MAILDEV_OUTGOING_HOST=$(MAILDEV_OUTGOING_HOST) \
		-e MAILDEV_SMTP_PORT=$(MAILDEV_SMTP_PORT) \
		-e MAILDEV_OUTGOING_USER=$(MAILDEV_OUTGOING_USER) \
		-e MAILDEV_OUTGOING_PASS=$(MAILDEV_OUTGOING_PASS) \
		-e MAILDEV_OUTGOING_SECURE=$(MAILDEV_OUTGOING_SECURE) \
		--name email-orders-maildev-temp --rm maildev/maildev:latest

docker-ngrok-backend: .env
	@docker run -it \
		-p $(NGROK_PORT):4040 \
		-e NGROK_AUTHTOKEN=$(NGROK_AUTHTOKEN) \
		--name email-orders-backend-ngrok-temp --rm ngrok/ngrok:latest \
		http host.docker.internal:$(APP_PORT)

docker-backend-shell:
	@docker exec -it email-orders:backend /bin/bash

docker-frontend-shell:
	@docker exec -it email-orders:frontend /bin/bash

docker-stop:
	@docker compose down

docker-delete:
	@docker compose rm -s -f -v database
	@docker rmi mariadb
	@docker compose rm -s -f -v backend
	@docker rmi email-orders:backend
	@docker compose rm -s -f -v frontend
	@docker rmi email-orders:frontend

delete-dependencies:
	@rm -rf backend/vendor
	@rm -rf frontend/node_modules
	@rm -rf frontend/.next

delete-database-data:
	@rm -rf .db-data

remove-all:
	@make docker-delete
	@make delete-dependencies
	@make delete-database-data

reset-backend:
	@docker compose rm -s -f -v backend
	@docker rmi email-orders:backend
	@rm -rf backend/vendor
	@docker compose build
	@docker compose run --rm backend make install
	@docker compose up -d

reset-frontend:
	@docker compose rm -s -f -v frontend
	@docker rmi email-orders:frontend
	@rm -rf frontend/node_modules
	@rm -rf frontend/.next
	@docker compose up -d

reset-all:
	@make remove-all
	@make docker-install
	@make docker-start
