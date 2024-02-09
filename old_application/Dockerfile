FROM php:7.4-apache

#copy application inside container
COPY app /var/www/html

# set workdir to html site
WORKDIR /var/www/html

# enable php mysqli to connect to database
RUN docker-php-ext-install mysqli

EXPOSE 80

