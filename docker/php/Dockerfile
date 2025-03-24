FROM php:8.3-fpm

# composer イメージ内から特定のファイルをコピー
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 必要パッケージと PHP 拡張のインストール
RUN apt-get update && apt-get install -y \
    git \
    zip unzip \
    curl \
    gnupg \
    ca-certificates \
    && docker-php-ext-install pdo_mysql

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest
