FROM php:7.4-fpm-alpine

WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

ADD etc/php-xdebug.ini /etc/php/7.4/cli/conf.d/zz-xdebug-settings.ini
ADD etc/php-xdebug.ini /etc/php/7.4/fpm/conf.d/zz-xdebug-settings.ini
ADD etc/php-xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del pcre-dev ${PHPIZE_DEPS}
RUN docker-php-ext-install pdo_mysql
RUN apk --no-cache add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#RUN apk add --no-cache bash



#    && apt-get -y --no-install-recommends install php7.4-pgsql php7.4-bcmath php-redis php-xdebug php7.4-intl php7.4-soap php-yaml \