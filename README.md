
# Product API

Une API RESTful simple pour la gestion de produits, développée avec Symfony 6.4.

## Table des matières
- [Fonctionnalités](#fonctionnalités)
- [Installation](#installation)
  - [Option 1 : Installation locale](#option-1--installation-locale)
    - [Prérequis](#prérequis)
    - [Méthode rapide (avec Make)](#méthode-rapide-avec-make)
    - [Installation manuelle détaillée](#installation-manuelle-détaillée)
  - [Option 2 : Installation avec Docker](#option-2--installation-avec-docker)
    - [Prérequis](#prérequis-1)
    - [Méthode rapide (avec Make)](#méthode-rapide-avec-make-1)
    - [Installation manuelle détaillée](#installation-manuelle-détaillée-1)
- [Utilisation](#utilisation)
  - [Accès à l'API](#accès-à-lapi)
  - [Endpoints disponibles](#endpoints-disponibles)
  - [Notes](#notes)

## Fonctionnalités
- CRUD complet pour les produits
- Pagination des résultats avec paramètres personnalisables (page, limit)
- Documentation Swagger/OpenAPI
- Validation des données
- Gestion automatique des statuts de stock

## Installation
Le projet peut être installé soit localement, soit avec Docker. Un Makefile est fourni pour simplifier les commandes courantes.

### Option 1 : Installation locale

#### Prérequis
- PHP 8.3
- Composer
- Symfony CLI (optionnel)

#### Méthode rapide (avec Make)
```bash
# Cloner le projet
git clone 
cd product-api

# Installation complète
make install

# Démarrer le serveur
make start   # Utilisera symfony serve ou php -S selon disponibilité
```

Pour utiliser un port différent :
```bash
PORT=8080 make start
```

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
5. (Optionnel) Charger les données de test
```
php bin/console doctrine:fixtures:load
```
6. Démarrer le serveur
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

#### Méthode rapide (avec Make)
```bash
# Cloner le projet
git clone 
cd product-api

# Installation et démarrage
make install-docker
```

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
6. (Optionnel) Charger les données de test
```
docker compose exec php php bin/console doctrine:fixtures:load
```
## Utilisation

### Accès à l'API
- API : http://localhost:8000/api
- Documentation Swagger : http://localhost:8000/api/doc

### Endpoints disponibles
- `GET /api/products` : Liste tous les produits
    - Paramètres de pagination :
        - `page` : Numéro de la page (défaut : 1)
        - `limit` : Nombre d'éléments par page (défaut : 10)
    - Exemple : `/api/products?page=2&limit=20`
- `GET /api/products/{id}` : Récupère un produit spécifique
- `POST /api/products` : Crée un nouveau produit
- `PATCH /api/products/{id}` : Met à jour un produit
- `DELETE /api/products/{id}` : Supprime un produit

### Notes
- La documentation de l'API est accessible via Swagger UI à `/api/doc`
- Les statuts de stock sont automatiquement mis à jour en fonction de la quantité
- Toutes les données sont validées avant l'enregistrement