FROM php:8.2-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli 

COPY ./ /var/www/carpeta-zentryx-main


RUN sed -i 's#/var/www/html#/var/www/carpeta-zentryx-main#g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's#/var/www/html#/var/www/carpeta-zentryx-main#g' /etc/apache2/apache2.conf


RUN chown -R www-data:www-data /var/www/carpeta-zentryx-main
RUN chmod -R 755 /var/www/carpeta-zentryx-main


EXPOSE 80