FROM wordpress:php7.3-apache

RUN rm -rf /usr/src/wordpress/*

COPY wordpress/ /usr/src/wordpress

RUN chown -R www-data:www-data /usr/src/wordpress
RUN chmod -R 777 /usr/src/wordpress/wp-content
