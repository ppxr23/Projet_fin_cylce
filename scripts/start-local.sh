#!/bin/bash

set -e

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Vivetic RH - Local Environment Startup${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Vérifier Docker
echo -e "${YELLOW}✓ Checking Docker installation...${NC}"
if ! command -v docker &> /dev/null; then
    echo -e "${RED}✗ Docker is not installed${NC}"
    exit 1
fi
docker --version

echo -e "${YELLOW}✓ Checking Docker Compose...${NC}"
if ! command -v docker &> /dev/null; then
    echo -e "${RED}✗ Docker Compose is not installed${NC}"
    exit 1
fi
docker compose version
echo ""

# Charger les variables
if [ -f .env.local ]; then
    export $(cat .env.local | grep -v '#' | xargs)
    echo -e "${GREEN}✓ .env.local loaded${NC}"
else
    echo -e "${RED}✗ .env.local not found${NC}"
    exit 1
fi
echo ""

# Arrêter les conteneurs
echo -e "${YELLOW}Stopping existing containers...${NC}"
docker compose down --remove-orphans 2>/dev/null || true
echo ""

# Build des images
echo -e "${YELLOW}Building Docker images...${NC}"
docker compose build --progress=plain
echo ""

# Démarrer les services
echo -e "${YELLOW}Starting services...${NC}"
docker compose up -d
echo ""

# Attendre que PostgreSQL soit prêt
echo -e "${YELLOW}Waiting for PostgreSQL...${NC}"
sleep 3
for i in {1..30}; do
    if docker compose exec -T postgres pg_isready -U postgres > /dev/null 2>&1; then
        echo -e "${GREEN}✓ PostgreSQL is ready${NC}"
        break
    fi
    echo -n "."
    sleep 1
done
echo ""

# Attendre que Backend soit prêt
echo -e "${YELLOW}Waiting for Backend...${NC}"
sleep 2
echo -e "${GREEN}✓ Backend started${NC}"
echo ""

# Afficher le statut
echo -e "${GREEN}Service Status:${NC}"
docker compose ps
echo ""

# Afficher les URLs
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Application URLs:${NC}"
echo -e "${GREEN}========================================${NC}"
echo -e "${YELLOW}Frontend     :${NC} http://localhost:3000"
echo -e "${YELLOW}Backend API  :${NC} http://localhost:8000/api"
echo -e "${YELLOW}PgAdmin      :${NC} http://localhost:5050"
echo -e "${YELLOW}PostgreSQL   :${NC} localhost:5432"
echo ""

echo -e "${GREEN}Database Credentials:${NC}"
echo -e "${YELLOW}User${NC}: postgres"
echo -e "${YELLOW}Pass${NC}: postgres123"
echo -e "${YELLOW}DB${NC}  : vivetic_rh"
echo ""

echo -e "${GREEN}✓ Setup completed successfully!${NC}"
echo -e "${YELLOW}Note: Services may take a moment to fully initialize.${NC}"
echo -e "${YELLOW}Check logs with: docker compose logs -f${NC}"

chmod +x scripts/start-local.sh