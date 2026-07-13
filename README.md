# Amoura

Application web de restaurant — réservation en ligne et programme de fidélité.

Projet fil rouge réalisé dans le cadre de la formation **CDA (Concepteur Développeur d'Applications)**.
La documentation complète (cahier des charges, méthodologie, modélisation BDD, conception UML,
architecture) se trouve dans le dossier [`CDA/`](./CDA).

## Stack technique

| Couche         | Technologie                                      |
|----------------|---------------------------------------------------|
| Backend        | PHP 8.3 · Symfony 7 (API REST)                     |
| Frontend       | React 19 (Vite)                                    |
| Base de données| MariaDB 10.11 (Doctrine ORM)                       |
| Authentification | JWT (LexikJWTAuthenticationBundle)               |
| Emails         | Symfony Mailer (Mailpit en local, Mailjet/SendGrid en prod) |
| Conteneurisation | Docker & Docker Compose                          |
| CI             | GitHub Actions                                     |

## Démarrage rapide (Docker)

Prérequis : [Docker Desktop](https://www.docker.com/products/docker-desktop/) installé et lancé.

```bash
cp .env.example .env
docker compose up --build
```

| Service              | URL                                    |
|----------------------|-----------------------------------------|
| Frontend (React)     | http://localhost:5173                   |
| Backend (API)        | http://localhost:8000/api               |
| Mailpit (emails de test) | http://localhost:8025               |
| Adminer (BDD)         | http://localhost:8080 (serveur `database`, user `amoura`) |

Une fois les conteneurs démarrés, initialisez la base de données (uniquement au premier lancement) :

```bash
docker compose exec backend php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec backend php bin/console doctrine:fixtures:load --no-interaction
```

### Comptes de test (créés par les fixtures)

| Rôle    | Email                              | Mot de passe |
|---------|-------------------------------------|--------------|
| Admin   | admin@amoura-restaurant.fr          | Admin1234    |
| Client  | client@amoura-restaurant.fr         | Client1234   |

## Développement sans Docker

### Backend

```bash
cd backend
composer install
cp .env .env.local   # puis adapter DATABASE_URL à votre MariaDB local
php bin/console lexik:jwt:generate-keypair --skip-if-exists
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
symfony server:start   # ou: php -S localhost:8000 -t public
```

### Frontend

```bash
cd frontend
npm install
cp .env.example .env   # VITE_API_URL doit pointer vers l'API backend
npm run dev
```

## Qualité & tests

```bash
# Backend
cd backend
vendor/bin/php-cs-fixer fix --dry-run --diff   # style de code (PSR-12)
vendor/bin/phpstan analyse                      # analyse statique (niveau 5)
vendor/bin/phpunit                               # tests unitaires + fonctionnels

# Frontend
cd frontend
npm run lint     # oxlint
npm run build    # build de production
```

La CI GitHub Actions (`.github/workflows/ci.yml`) exécute automatiquement ces vérifications sur
chaque push/pull request vers `main` et `develop`, ainsi que le build des images Docker.

## Organisation du dépôt

```
Amoura/
├── backend/          # API Symfony 7
├── frontend/          # SPA React (Vite)
├── docker-compose.yml # orchestration des services (dev)
├── CDA/               # livrables pédagogiques (cahiers des charges, jalons, maquettes)
└── .github/workflows/ # pipeline CI
```

## Stratégie Git

- `main` : versions stables (livrables de jalons), protégée
- `develop` : intégration continue des fonctionnalités
- `feature/*`, `fix/*` : branches de travail, fusionnées dans `develop` via pull request

Voir [`CDA/Jalon 2/methodologie_organisation.pdf`](<./CDA/Jalon 2/methodologie_organisation.pdf>) pour le détail
de la méthodologie et de la stratégie de branches.

## Fonctionnalités

- Consultation du menu, de l'histoire du restaurant, page contact
- Inscription / connexion (JWT)
- Réservation en ligne avec vérification de disponibilité en temps réel (date, créneau, couverts)
- Email de confirmation / annulation automatique
- Programme de fidélité : points, niveaux, échange de récompenses
- Interface d'administration : tableau de bord, gestion des réservations, tables, créneaux,
  clients et programme de fidélité

## Sécurité

- Mots de passe hachés (algorithme `auto`, bcrypt/argon2 selon la plateforme)
- Authentification par JWT signé (RS256), stateless
- Protection CORS restreinte au domaine du frontend
- Validation des données d'entrée (Symfony Validator) sur tous les endpoints
- Requêtes paramétrées via Doctrine ORM (pas de SQL brut concaténé)
- Suppression de compte RGPD : cascade sur les données personnelles associées
