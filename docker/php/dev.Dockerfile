# Dockerfile
FROM php:8.1-fpm as dev

# Install apcu
RUN pecl install apcu

# Set user/group
ENV USER=ubuntu
ENV GROUP=ubuntu

RUN groupadd -g 1000 ${GROUP} && \
    useradd -d /home/${USER} -s /bin/bash -u 1000 -g 1000 ${USER}

RUN mkdir /home/${USER}
RUN chown -R ${USER}:${GROUP} /home/${USER}

# Install php extensions
RUN apt-get update -y && apt-get install -y \
        curl \
        wget \
        zlib1g-dev \
        g++ \
        libfreetype6-dev \
        libwebp-dev \
        libpng-dev \
        libzip-dev \
        libmcrypt-dev \
        libicu-dev \
        libonig-dev \
        libpq-dev \
    && docker-php-ext-install sockets \
    && docker-php-ext-configure intl \
    && docker-php-ext-install iconv mysqli bcmath pdo_mysql zip \
    && docker-php-ext-install intl

ADD ./docker/php/php.ini /usr/local/etc/php/conf.d/40-custom.ini

WORKDIR /var/www

RUN echo 'max_execution_time=120' >> /usr/local/etc/php/conf.d/docker-php-maxexectime.ini;

ENV XDEBUG_MODE=coverage

RUN pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

USER ${USER}

CMD ["php","-S", "0.0.0.0:8000", "-t", "./public"]