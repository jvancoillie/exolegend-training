#syntax=docker/dockerfile:1.4
FROM composer/composer:2-bin AS composer_upstream

FROM php:7.3.9-cli-alpine as php-cli

LABEL authors="Kami"

RUN apk add --no-cache \
    make \
  ;

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions bcmath intl zip calendar wddx soap sockets

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer_upstream --link /composer /usr/bin/composer

WORKDIR /srv/app