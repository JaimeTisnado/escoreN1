FROM php:8.1-apache

# Instalar dependencias necesarias para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar tu código fuente al contenedor
COPY . /var/www/html/

# Dar permisos adecuados (opcional pero recomendado)
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto por defecto de Apache
EXPOSE 80
