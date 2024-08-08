include /.$(PWD)/.env
include /.$(PWD)/makelib/ansi-sgr.c
include /.$(PWD)/makelib/app-info.c

# targets
default:
	@echo "${BLUE_BACKGROUND}${BOLD}${WHITE} .::EMAIL ORDERS::. ${NORMAL} ${GREEN}Makefile${NORMAL}\n"
	@echo "${YELLOW}Frontend ${GREEN}${FRONTEND_VERSION}${NORMAL}"
	@echo "${YELLOW}Backend  ${GREEN}${BACKEND_VERSION}${NORMAL}\n"
	@echo "${YELLOW}Author  ${NORMAL}${AUTHOR}"
	@echo "${YELLOW}Website  ${CYAN}${WEBSITE}${NORMAL}\n"
	@echo "${YELLOW}Usage:${NORMAL}"
	@echo "  ${BOLD}$$ ${NORMAL}make [target] [options]\n"
	@echo "${YELLOW}Targets:${NORMAL}"
	@echo "  ${GREEN}docker-install${NORMAL}		Build all the app containers, starts the database and install backend dependencies"
	@echo "  ${GREEN}docker-start${NORMAL}			Start all the app containers"
	@echo "  ${GREEN}docker-maildev${NORMAL}		Start maildev temporary container (requires ${GREEN}MAILDEV_WEB_PORT${NORMAL} and ${GREEN}MAILDEV_SMTP_PORT${NORMAL} from ${GREEN}.env${NORMAL})"
	@echo "  ${GREEN}docker-ngrok-backend${NORMAL}		Start ngrok temporary container for backend (requires an ${GREEN}ngrok API key${NORMAL})"
	@echo "  ${GREEN}docker-backend-shell${NORMAL}		Open the bash console of the backend container"
	@echo "  ${GREEN}docker-frontend-shell${NORMAL}		Open the bash console of the frontend container"
	@echo "  ${GREEN}docker-stop${NORMAL}			Stop all the app containers"
	@echo "  ${GREEN}docker-delete${NORMAL}			Delete all the app containers and respective images"
	@echo "  ${GREEN}delete-dependencies${NORMAL}		Delete the backend and frontend dependencies"
	@echo "  ${GREEN}delete-database-data${NORMAL}		Delete the app database data"
	@echo "  ${GREEN}remove-all${NORMAL}			Delete all containers, images, dependencies and database data"
	@echo "  ${GREEN}reset-backend${NORMAL}			Delete, rebuild, and start the backend container, image and dependencies"
	@echo "  ${GREEN}reset-frontend${NORMAL}		Delete, rebuild, and start the frontend container, image and dependencies"
	@echo "  ${GREEN}reset-all${NORMAL}			Delete, rebuild, and start all containers, images, dependencies and database data\n"
	@echo "${YELLOW}Options:${NORMAL}"
	@echo "  Consult the official ${GREEN}Makefile${NORMAL} docs by running ${GREEN}make -h${NORMAL} or visiting ${CYAN}https://www.gnu.org/software/make/manual/${NORMAL}"

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
