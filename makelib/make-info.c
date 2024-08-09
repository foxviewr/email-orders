include ./ansi-sgr.c
include ./app-info.c

define make_info
    @echo "${BLUE_BACKGROUND}${BOLD}${WHITE} .::EMAIL ORDERS::. ${NORMAL} ${GREEN}Makefile${NORMAL}\n"
    @echo "${YELLOW}Frontend ${GREEN}${FRONTEND_VERSION}${NORMAL}"
    @echo "${YELLOW}Backend  ${GREEN}${BACKEND_VERSION}${NORMAL}\n"
    @echo "${YELLOW}Author   ${NORMAL}${AUTHOR}"
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
endef
