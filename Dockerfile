# Dockerfile
FROM php:8.3-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de l'application
COPY . .

# Créer le dossier var s'il n'existe pas et configurer les permissions
RUN mkdir -p var && \
    chown -R www-data:www-data . && \
    chmod -R 777 var

# Installer les dépendances PHP
RUN composer install --no-interaction

# S'assurer que www-data a les droits sur les fichiers générés
RUN chown -R www-data:www-data .