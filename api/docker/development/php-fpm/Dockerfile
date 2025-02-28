FROM php:8.4-fpm-alpine3.23

ENV XDEBUG_VERSION=3.4.7

RUN apk add --no-cache libpq-dev fcgi git linux-headers \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && git clone --branch $XDEBUG_VERSION --depth 1 https://github.com/xdebug/xdebug.git /usr/src/php/ext/xdebug \
    && docker-php-ext-configure xdebug --enable-xdebug-dev \
    && docker-php-ext-install pdo_pgsql xdebug \
    && apk del git linux-headers

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./common/php/conf.d /usr/local/etc/php/conf.d
COPY ./common/php/php-fpm.d /usr/local/etc/php-fpm.d
COPY ./development/php/conf.d /usr/local/etc/php/conf.d
COPY ./development/php-fpm/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY ./development/php-fpm/docker-entrypoint-host.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint-host.sh

ENTRYPOINT ["docker-entrypoint-host.sh", "docker-php-entrypoint"]
CMD ["php-fpm"]

RUN apk add --no-cache shadow

ARG UID=1000
ARG GID=1000

RUN groupmod -g "${GID}" www-data && usermod -u "${UID}" www-data

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s \
    CMD REDIRECT_STATUS=true SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1
