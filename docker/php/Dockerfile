FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

# 必要な依存ライブラリをインストールし、GD拡張を有効化
RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip \
  libpng-dev libjpeg-dev libfreetype6-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install pdo_mysql zip gd

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && composer self-update

WORKDIR /var/www
