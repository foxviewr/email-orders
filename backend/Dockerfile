FROM php:8.2.11-fpm

# Set the working directory
WORKDIR /var/www/html

# MacOS staff group's gid is 20, so is the dialout group in alpine linux. We're not using it, let's just remove it.
RUN delgroup dialout

# Create and set group and user laravel
RUN addgroup --gid 1000 --system laravel
RUN adduser --system --gid 1000 --disabled-password --shell /bin/sh --uid 1000 laravel

RUN sed -i "s/user = www-data/user = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = laravel/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Update package manager and install useful tools
RUN apt-get update && apt-get -y install apt-utils nano wget dialog vim make

# Install important libraries
RUN apt-get -y install --fix-missing \
    apt-utils \
    build-essential \
    git \
    curl \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    libbz2-dev \
    locales \
    libmcrypt-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev

# Install Redis
RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the user laravel
USER laravel

# Define the entry point
CMD ["php-fpm"]
