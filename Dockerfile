FROM php:8.5.5-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    cron \
    && docker-php-ext-install mysqli pdo pdo_pgsql pgsql zip gd \
    && a2enmod rewrite

WORKDIR /var/www/html

COPY src/ .
COPY cron/vpn-collector /etc/cron.d/vpn-collector

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod 0644 /etc/cron.d/vpn-collector \
    && crontab /etc/cron.d/vpn-collector

EXPOSE 80
CMD ["sh", "-c", "cron && apache2-foreground"]