FROM tugboatqa/php:8.2-apache

RUN apt-get update && apt-get install -y sqlite3 php-sqlite3 libsqlite3-dev && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite
