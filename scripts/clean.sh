#!/bin/bash

set -e

echo "Cleaning up..."

# Arrêter les conteneurs
docker compose down --remove-orphans

# Supprimer les volumes
docker volume prune -f

# Supprimer les images inutilisées
docker image prune -f

echo "Cleanup completed."

chmod +x scripts/clean.sh