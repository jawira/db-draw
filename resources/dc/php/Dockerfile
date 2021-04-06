FROM php:7.3-cli-buster

WORKDIR "/app"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY resources/dc/php/zzz-php-settings.ini  /usr/local/etc/php/conf.d/

RUN set -ex \
    && echo "### apg-get ###" \
    && apt-get update \
    && apt-get --yes --no-install-recommends install \
        wget \
        libxml2-dev \
        libicu-dev \
        mysql-common \
    && echo "### PHP extensions ###" \
    && docker-php-source extract \
    && pecl install xdebug \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        xml \
        mbstring \
        mysqli \
        pdo_mysql \
    && docker-php-ext-enable \
        xdebug \
    && echo "### Phing ###" \
    && wget -nv -O /usr/local/bin/phing https://github.com/phingofficial/phing/releases/download/3.0.0-RC1/phing-3.0.0-RC1.phar \
    && chmod +x /usr/local/bin/phing \
    && echo "### Cleaning ###" \
    && docker-php-source delete \
    && apt-get clean \
    && rm -rf \
        /var/lib/apt/lists/* \
        /tmp/* \
        /var/tmp/* \
        /usr/share/doc/*

COPY --from=composer /usr/bin/composer /usr/bin/composer
