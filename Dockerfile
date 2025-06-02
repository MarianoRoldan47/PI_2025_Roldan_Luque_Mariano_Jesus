# Usa una imagen base de PHP con FPM
FROM php:8.2-fpm

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    default-mysql-client \
    ca-certificates \
    openssl \
    && docker-php-ext-install pdo pdo_mysql zip bcmath gd

# Actualizar certificados CA
RUN update-ca-certificates

RUN { \
    echo 'openssl.cafile = /etc/ssl/certs/ca-certificates.crt'; \
    echo 'curl.cainfo = /etc/ssl/certs/ca-certificates.crt'; \
    } > /usr/local/etc/php/conf.d/docker-php-ssl-ca.ini

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instala Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Establecer el directorio de trabajo
WORKDIR /var/www

# Copiar primero los archivos de configuración de dependencias
COPY composer.json composer.lock ./

# Instalar dependencias de Composer - solo descarga sin autoload aún
RUN composer install --no-scripts --no-autoloader

# Copiar el resto de los archivos
COPY . .

# Generar autoload optimizado
RUN composer dump-autoload --optimize

# Ajustar los permisos de los archivos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage

