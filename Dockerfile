FROM php:8.2-fpm

WORKDIR /var/www/users_service

# Install PHP extensions and other dependencies
RUN apt-get update && apt-get install -y \
    # git \
    unzip \
    libpq-dev \
    libzip-dev \
    librabbitmq-dev \
    libssh-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Symfony application files
COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Symfony dependencies
RUN composer install --optimize-autoloader

# Expose port
EXPOSE 9000

CMD ["php-fpm"]
