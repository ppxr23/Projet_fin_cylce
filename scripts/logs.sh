#!/bin/bash

SERVICE=${1:-all}

case $SERVICE in
  backend)
    docker compose logs -f vivetic-backend
    ;;
  frontend)
    docker compose logs -f vivetic-frontend
    ;;
  postgres)
    docker compose logs -f vivetic-postgres
    ;;
  pgadmin)
    docker compose logs -f vivetic-pgadmin
    ;;
  *)
    docker compose logs -f
    ;;
esac

chmod +x scripts/logs.sh