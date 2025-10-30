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

# Configurer et installer extensions PHP
RUN docker-php-ext-configure gd --with-jpeg \
 && docker-php-ext-install pdo pdo_pgsql gd zip intl opcache

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier uniquement les fichiers nécessaires au build
COPY backend/composer.json backend/composer.lock ./

# Installer les dépendances PHP (sans les dev si build prod)
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-interaction

# Copier le reste du code backend
COPY backend/ .

# Générer autoloader et scripts
RUN composer dump-autoload --optimize && composer run-script post-install-cmd

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

# Copier le code backend et vendor
COPY --from=builder /app /app

# Définir l’environnement Symfony
ENV APP_ENV=dev

# Créer un utilisateur non-root (sécurité)
RUN adduser -D symfony && chown -R symfony:symfony /app
USER symfony

# Exposer le port
EXPOSE 8000

# Lancer le serveur Symfony intégré
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
