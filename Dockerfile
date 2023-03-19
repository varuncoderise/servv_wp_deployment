FROM wordpress:php8.0-apache

RUN rm -rf /usr/src/wordpress/*

COPY wordpress/ /usr/src/wordpress
COPY php/php.ini-production /usr/local/etc/php/php.ini

RUN chown -R www-data:www-data /usr/src/wordpress
