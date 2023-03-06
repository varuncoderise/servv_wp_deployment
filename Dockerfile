FROM wordpress:php8.2-apache

RUN rm -rf /usr/src/wordpress/*

COPY wordpress/ /usr/src/wordpress

RUN chown -R www-data:www-data /usr/src/wordpress
