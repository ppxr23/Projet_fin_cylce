Installation:
-git checkout (vers le branche)
-composer install
-yarn install
-yarn encore dev --watch
-php -S 127.0.0.1 -t public

# Vivetic RH Platform - Local Setup

## Démarrer
```bash
./scripts/start-local.sh
```

## Arrêter
```bash
./scripts/stop-local.sh
```

## Voir les logs
```bash
./scripts/logs.sh
```

## URLs

| Service | URL |
|---------|-----|
| Frontend | http://localhost:3000 |
| Backend | http://localhost:8000 |
| PgAdmin | http://localhost:5050 |
| PostgreSQL | localhost:5432 |

## Identifiants

- User: postgres
- Pass: postgres123
- DB: vivetic_rh