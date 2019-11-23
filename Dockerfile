FROM php:apache
RUN rm /etc/apt/preferences.d/no-debian-php
RUN apt-get update && apt-get install -y php-gd php-curl php-mysql 
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY . /var/www/html/
