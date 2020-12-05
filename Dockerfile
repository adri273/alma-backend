FROM php:7.4.13-apache-buster

COPY api/ /var/www/html

EXPOSE 80
