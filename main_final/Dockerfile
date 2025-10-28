FROM php:8.2-apache

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli 

COPY ./PlataformaLudica /var/www/carpeta-zentryx-main/PlataformaLudica


RUN sed -i 's#/var/www/html#/var/www/carpeta-zentryx-main#g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's#/var/www/html#/var/www/carpeta-zentryx-main#g' /etc/apache2/apache2.conf


RUN chown -R www-data:www-data /var/www/carpeta-zentryx-main/PlataformaLudica
RUN chmod -R 755 /var/www/carpeta-zentryx-main/PlataformaLudica


EXPOSE 80