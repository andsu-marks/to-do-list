FROM php:8.2-cli

WORKDIR /var/www/html
RUN apt-get update && apt-get install -y unzip git zip
COPY . .

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && mv composer.phar /usr/local/bin/composer \
    && rm composer-setup.php
