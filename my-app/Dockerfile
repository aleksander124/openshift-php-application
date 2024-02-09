FROM php:7.4-apache

#copy application inside container
COPY src /var/www/html

# set workdir to html site
WORKDIR /var/www/html

# enable php mysqli to connect to database
RUN docker-php-ext-install mysqli

# Configure Apache to listen on port 8080
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Start Apache
CMD ["apache2-foreground"]

EXPOSE 8080