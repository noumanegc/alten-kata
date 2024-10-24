FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN mkdir -p var && \
    chown -R www-data:www-data . && \
    chmod -R 777 var

RUN composer install --no-interaction

RUN chown -R www-data:www-data .