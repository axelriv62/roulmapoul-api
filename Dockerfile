# Dockerfile.dev (Laravel dev)
FROM php:8.2-cli

# Installer les dépendances systèmes
RUN apt-get update -y && apt-get install -y libmcrypt-dev
RUN apt-get install -y git
RUN apt-get install -y libzip-dev
RUN apt-get install -y unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /app

# Copier les fichiers du projet dans le conteneur
COPY . .

# Installer les dépendances PHP avec Composer
RUN composer install

# Générer la clé Laravel, migrer et seed
RUN touch database/database.sqlite
RUN php artisan config:clear
RUN php artisan key:generate
RUN php artisan migrate:fresh --seed

# Exposer le port
EXPOSE 8000

# Lancer le serveur de développement
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

