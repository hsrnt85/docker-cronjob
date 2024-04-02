FROM php:8.1.5-apache

USER root 

WORKDIR /var/www/html 

RUN apt-get update && apt-get install -y \
         libpng-dev \
         zlib1g-dev \
         libxml2-dev \
         libzip-dev \
         libonig-dev \
         zip \
         curl \
         unzip \
	    git \
         cron \
     && docker-php-ext-configure gd \
     && docker-php-ext-install -j$(nproc) gd \
     && docker-php-ext-install pdo_mysql \
     && docker-php-ext-install mysqli \
     && docker-php-ext-install zip \
     && docker-php-source delete 

# COPY .ENV File
COPY ./.env.example /var/www/html/.env

COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./ /var/www/html/
    
# Add cron job file
ADD cronjobs /etc/cron.d/cronjobs
# Give execution rights on the cron job file
RUN chmod 0644 /etc/cron.d/cronjobs
# Apply cron job
RUN crontab /etc/cron.d/cronjobs
# Start cron service
CMD ["cron", "-f"]

RUN chown -R www-data:www-data /var/www/html \
     && a2enmod rewrite 
 
RUN apt-get update && apt-get upgrade -y

# CMD ["/usr/local/bin/start"]

