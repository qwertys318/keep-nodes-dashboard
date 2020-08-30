FROM hermsi/alpine-fpm-php:7.4.2

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -f composer-setup.php

COPY . /var/www/html

