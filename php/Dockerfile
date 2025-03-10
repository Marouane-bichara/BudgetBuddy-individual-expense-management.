FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    gnupg

RUN docker-php-ext-install pdo pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update Apache DocumentRoot
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

CMD ["apache2-foreground"]
