FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

COPY api/ /var/www/html/api/
COPY site/ /var/www/html/

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80
