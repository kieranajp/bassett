FROM php:7.1-fpm-alpine

ENV COMPOSER_VERSION=1.3.2 \
    PHP_EXT_XDEBUG_VERSION=2.5.0 \
    APP_NAME='Bassett'

# Install PHP dependencies
# See https://github.com/docker-library/php
RUN set -ex \
    && apk add --no-cache --virtual .build-dependencies ${PHPIZE_DEPS} \
    && docker-php-ext-install opcache pdo bcmath \
    && pecl install xdebug-${PHP_EXT_XDEBUG_VERSION} \
    && docker-php-ext-enable xdebug \
    && apk del .build-dependencies

# Install Composer
# See https://github.com/composer/docker/blob/master/1.3/Dockerfile
RUN set -ex \
    && curl -s -f -L -o /tmp/installer.php https://raw.githubusercontent.com/composer/getcomposer.org/2091762d2ebef14c02301f3039c41d08468fb49e/web/installer \
    && php -r " \
    \$signature = '55d6ead61b29c7bdee5cccfb50076874187bd9f21f65d8991d46ec5cc90518f447387fb9f76ebae1fbbacf329e583e30'; \
    \$hash = hash('SHA384', file_get_contents('/tmp/installer.php')); \
    if (!hash_equals(\$signature, \$hash)) { \
        unlink('/tmp/installer.php'); \
        echo 'Integrity check failed, installer is either corrupt or worse.' . PHP_EOL; \
        exit(1); \
    }" \
    && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION} \
    && rm /tmp/installer.php \
    && composer diagnose --ansi --no-interaction

# Install application/service
ENV APP_DIR /server/http
WORKDIR ${APP_DIR}/public

## Copy PHP ini based of off https://github.com/php/php-src/blob/PHP-7.1.1/php.ini-production
COPY php.ini ${PHP_INI_DIR}/

RUN set -ex \
    ## Customize PHP fpm configuration
    && sed -i -e "s/;clear_env\s*=\s*no/clear_env = no/g" /usr/local/etc/php-fpm.conf \
    && sed -i -e "s/;request_terminate_timeout\s*=[^\n]*/request_terminate_timeout = 300/g" /usr/local/etc/php-fpm.conf \
    && php-fpm --test \
    ## Prepare filesystem
    && mkdir -p ${APP_DIR}/var/cache \
    && chown www-data -R ${APP_DIR}/var/cache \
    && mkdir /var/log/${APP_NAME} \
    && touch /var/log/${APP_NAME}/app.log \
    && chown www-data /var/log/${APP_NAME}/app.log
