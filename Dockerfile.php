FROM php:8.2-apache

# Installa estensioni PHP necessarie
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install zip

# Installa Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Imposta la cartella di lavoro
WORKDIR /var/www/html

# Copia i file del sito
COPY . /var/www/html

# Abilita mod_rewrite per Apache
RUN a2enmod rewrite

# Espone la porta 80
EXPOSE 80
