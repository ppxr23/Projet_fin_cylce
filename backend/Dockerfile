FROM php:8.1-fpm-alpine AS builder

WORKDIR /app

RUN apk add --no-cache \
    curl \
    git \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    zlib-dev

RUN docker-php-ext-install pdo pdo_pgsql gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY backend/ .

RUN composer install --no-dev --optimize-autoloader 2>&1 | grep -v "Deprecation Notice" || true

FROM php:8.1-fpm-alpine

WORKDIR /app

RUN apk add --no-cache postgresql-libs curl

COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
COPY --from=builder /app /app

EXPOSE 9000

HEALTHCHECK --interval=10s --timeout=5s --retries=5 \
    CMD curl -f http://localhost:9000/ping || exit 1

CMD ["php-fpm"]