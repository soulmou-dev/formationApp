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

# Activer mod_rewrite pour Apache
RUN a2enmod rewrite ssl

# Modifie le DocumentRoot vers /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# installer composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# Définir le répertoire de travail (optionnel)
WORKDIR /var/www/html

RUN mkdir -p /etc/ssl/private && chmod 700 /etc/ssl/private

# Ajoute une config SSL Apache
COPY ./docker/apache/formationapp-ssl.conf /etc/apache2/sites-available/formationapp-ssl.conf

# Crée des certificats auto-signés (si pas déjà fournis)
RUN openssl req -x509 -nodes -days 365 \
    -subj "/C=FR/ST=Paris/L=Paris/O=Dev/CN=localhost" \
    -newkey rsa:2048 \
    -keyout /etc/ssl/private/apache-selfsigned.key \
    -out /etc/ssl/certs/apache-selfsigned.crt

RUN a2ensite formationapp-ssl

# Droits sur www-data
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80 443

CMD ["apache2-foreground"]