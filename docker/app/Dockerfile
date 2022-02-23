# https://laraweb.net/environment/8652/
# https://www.engilaboo.com/laravel-docker/#rtoc-2
FROM php:8.0-apache

# apacheの設定
ADD 000-default.conf /etc/apache2/sites-enabled/

# composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ミドルウェアのインストール
RUN apt-get update && apt-get install -y git vim \
    && docker-php-ext-install pdo_mysql
    
# エラーが出てしまったapacheの設定
# RUN sed -i 's!/var/www/html!/var/www/app/public!g' /etc/apache2sites-available/000-default.conf