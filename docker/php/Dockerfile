###################################
### PHP Docker with some extras ###
###################################

FROM php:7.0.32-apache
RUN apt-get update
RUN apt-get install -y autoconf pkg-config libssl-dev
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb