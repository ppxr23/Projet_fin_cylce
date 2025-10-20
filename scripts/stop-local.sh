#!/bin/bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}Stopping Vivetic RH services...${NC}"
docker compose down

echo -e "${GREEN}✓ All services stopped.${NC}"

chmod +x scripts/stop-local.sh