FROM php:8.3-apache

# Installer les dépendances nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    git \
    zip \
    && docker-php-ext-install intl mbstring pdo pdo_mysql zip opcache

# Activer mod_rewrite
RUN a2enmod rewrite

# Modifier le DocumentRoot vers /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Installer Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier uniquement les fichiers nécessaires pour composer (cache Docker)
COPY composer.json composer.lock ./

# Installer les dépendances PHP avec Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copier les fichiers de l'application
COPY . .

# Définir les bons droits pour Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Travailler dans ce répertoire
WORKDIR /var/www/html

# Expose uniquement HTTP (Render s'occupe du SSL)
EXPOSE 80

CMD ["apache2-foreground"]