FROM php:8.5.2-fpm-alpine

RUN set -eux; \
  apk add --no-cache bash coreutils curl icu-libs \
  ; \
  apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS linux-headers icu-dev \
  ; \
  docker-php-ext-install -j"$(nproc)" intl; \
  pecl install mongodb; \
  docker-php-ext-enable mongodb; \
  pecl install xdebug; \
#  docker-php-ext-enable xdebug; \
  rm -rf /tmp/pear; \
  apk del .build-deps

COPY conf.d/custom.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY conf.d/xdebug.ini /usr/local/etc/php/conf.d/99-xdebug.ini

WORKDIR /app
CMD ["php-fpm"]