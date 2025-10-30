# === Étape 1 : Build avec Composer et extensions ===
FROM php:8.3-fpm-alpine AS builder

WORKDIR /app

# Installer dépendances de compilation et PHP extensions
RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    postgresql-dev \
    zlib-dev

RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install pdo pdo_pgsql gd zip intl opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le code backend
COPY backend/ .

# Définir l'environnement Symfony
ENV APP_ENV=dev

COPY backend/.env.prod .env

# Installer les dépendances PHP
RUN composer install

# === Étape 2 : Image finale ===
FROM php:8.3-fpm-alpine

WORKDIR /app

# Installer libs nécessaires à GD et PostgreSQL
RUN apk add --no-cache \
    libpng \
    libjpeg-turbo \
    libzip \
    icu \
    postgresql-libs \
    bash \
    curl

# Copier extensions compilées et config PHP
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Copier le code backend
COPY --from=builder /app /app

# Pour dev : lancer Symfony server intégré
EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
