# https://laraweb.net/environment/8652/
# https://www.engilaboo.com/laravel-docker/#rtoc-2
# php8.0 apache2.4.52
FROM php:8.0-apache

# composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ミドルウェアのインストール
RUN apt-get update && apt-get install -y git vim \
    && docker-php-ext-install pdo_mysql

# Node.jsをインストール
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt-get install -y nodejs npm

# apacheの設定
ADD 000-default.conf /etc/apache2/sites-enabled/
RUN a2enmod rewrite && service apache2 restart