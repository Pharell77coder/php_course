FROM php:8.2-apache

# Installer les extensions nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activer mod_rewrite (utile pour les frameworks)
RUN a2enmod rewrite

# Copier le fichier d'entrée PHP
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Configuration supplémentaire (facultatif)
EXPOSE 80
