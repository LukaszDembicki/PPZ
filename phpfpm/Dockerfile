FROM phpdockerio/php74-fpm:latest
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

ADD etc/php-xdebug.ini /etc/php/7.4/cli/conf.d/zz-xdebug-settings.ini
ADD etc/php-xdebug.ini /etc/php/7.4/fpm/conf.d/zz-xdebug-settings.ini

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php7.4-pgsql php7.4-bcmath php-redis php-xdebug php7.4-intl php7.4-soap php-yaml \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && usermod -u 1000 www-data \
    && groupmod -g 1000 www-data