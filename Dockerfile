FROM php:8.2-apache

# Copy project files into container
COPY . /var/www/html/php

# Enable Apache mod_rewrite (अगर ज़रूरत पड़े तो)
RUN a2enmod rewrite
