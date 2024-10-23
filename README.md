
# Product API

Une API RESTful simple pour la gestion de produits, développée avec Symfony 6.4.

## Installation
Le projet peut être installé soit localement, soit avec Docker. Un Makefile est fourni pour simplifier les commandes courantes.

### Option 1 : Installation locale

#### Prérequis
- PHP 8.3
- Composer
- Symfony CLI (optionnel)

#### Installation manuelle détaillée
1. Cloner le projet
```
git clone <URL_DU_REPO>
cd product-api
```
2. Installer les dépendances
```
composer install
```
3. Configurer la base de données dans `.env`
```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```
4. Créer la base de données et appliquer les migrations
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
5. Démarrer le serveur
```
# Avec Symfony CLI
symfony serve

# Ou avec le serveur PHP
php -S localhost:8000 -t public/
```

### Option 2 : Installation avec Docker

#### Prérequis
- Docker
- Docker Compose

#### Installation manuelle détaillée
1. Cloner le projet
```
git clone <URL_DU_REPO>
cd product-api
```
2. Configurer la base de données dans `.env`
```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```
3. Démarrer les containers
```
docker compose up -d
```
4. Installez les dépendances :
```
docker compose exec php composer install
```
6. Créer la base de données
```
docker compose exec php php bin/console doctrine:database:create

# Permission nécessaire uniquement pour SQLite
docker compose exec -u root php chmod 666 var/data.db
```
5. Appliquer les migrations
```
docker compose exec php php bin/console doctrine:migrations:migrate
```