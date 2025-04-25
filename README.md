# Roulmapoul

## Description

L'API Roulmapoul fourni les données nécessaires à la gestion d'une agence de location de voitures.

## Auto-évaluation

Nous sommes très fiers d'avoir réussi à réaliser l'entièreté de l'API, les fonctionnalités sont toutes disponibles et fonctionnelles.

## Répartition des tâches

| Tâches                                                          | Membre(s)                   |
|-----------------------------------------------------------------|-----------------------------|
| Authentification                                                | Axel                        |
| Contrôleurs                                                     | Carl                        |
| Requests                                                        | Carl                        |
| Resources                                                       | Carl                        |
| Mails                                                           | Carl                        |
| Migrations                                                      | Quentin, Axel, Bylel, Carl  |
| Factories                                                       | Quentin, Axel, Bylel Carl   |
| Seeders                                                         | Quentin, Axel, Bylel, Carl  | 
| Repositories                                                    | Carl                        |
| Permissions, rôles                                              | Carl                        |
| Modèles                                                         | Carl                        |
| Routes                                                          | Carl, Bylel                 |
| CI/CD                                                           | Carl, Bylel                 |
| Tests                                                           | Carl, Bylel                 |
| Dockerfile                                                      | Bylel                       |
| Système d'informations (dictionnaire de données, diagramme UML) | Axel, Quention, Carl, Bylel |

## Récupération du projet

```bash
# Cloner le projet
git clone https://gitlab.univ-artois.fr/sae-s4-24-25-equipe07/roulmapoul-api

# Se placer dans le répertoire du projet
cd roulmapoul-api

# Installer les dépendances
composer install

# Configurations
cp.env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate
php artisan db:seed

# Lancer le serveur
php artisan serve
```

## Recevoir les mails
- Lancer le serveur de mail
```bash
mailpit
```
- Lancer la queue de jobs serveur de l'API
```bash
php artisan queue:work
```
