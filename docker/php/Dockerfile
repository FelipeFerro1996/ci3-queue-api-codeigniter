FROM php:7.4-fpm

# Repositórios históricos do Debian Bullseye
RUN printf 'deb http://archive.debian.org/debian bullseye main\n' \
    > /etc/apt/sources.list \
    && rm -f /etc/apt/sources.list.d/* \
    && echo 'Acquire::Check-Valid-Until "false";' \
    > /etc/apt/apt.conf.d/99no-check-valid

# Dependências e extensões PHP
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
    && docker-php-ext-install \
        pgsql \
        pdo_pgsql \
        zip \
    && rm -rf /var/lib/apt/lists/*

# Extensão Redis
RUN pecl install redis-5.3.7 \
    && docker-php-ext-enable redis

# Permitir que o PHP-FPM receba as variáveis do container
RUN echo 'clear_env = no' >> /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html

EXPOSE 9000