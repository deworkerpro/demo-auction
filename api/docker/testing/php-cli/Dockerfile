FROM php:8.3-cli-alpine3.19 AS builder

ENV REDIS_VERSION 6.0.1

RUN apk add --no-cache postgresql-dev git \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && git clone --branch $REDIS_VERSION --depth 1 https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
    && docker-php-ext-configure redis \
    && docker-php-ext-install pdo_pgsql intl redis opcache \
    && apk del git

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --version=2.2.10 --quiet

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --prefer-dist --no-progress --no-suggest --optimize-autoloader \
    && rm -rf /root/.composer/cache

### CLI ###

FROM php:8.3-cli-alpine3.19

ENV REDIS_VERSION 6.0.1

RUN apk add --no-cache postgresql-dev bash coreutils git \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && git clone --branch $REDIS_VERSION --depth 1 https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis \
    && docker-php-ext-configure redis \
    && docker-php-ext-install pdo_pgsql intl redis opcache \
    && apk del git

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/common/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/testing/php/conf.d /usr/local/etc/php/conf.d

COPY ./docker/common/wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

WORKDIR /app

COPY --from=builder /app ./
COPY ./ ./

USER app
