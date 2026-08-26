FROM php:8.2-apache

RUN apt-get update && apt-get install -y libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY api/ /var/www/html/api/
COPY site/ /var/www/html/

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
