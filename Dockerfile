# Dockerfile para Apache + PHP
FROM php:8.2-apache

# Instala extensões necessárias do PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Copia os arquivos do projeto para o container
COPY . /var/www/html/

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta padrão do Apache
EXPOSE 80
