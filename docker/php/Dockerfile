FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        $PHPIZE_DEPS \
        git \
        unzip \
    && git clone --depth 1 --branch 6.3.0 https://github.com/phpredis/phpredis.git /tmp/phpredis \
    && cd /tmp/phpredis \
    && phpize \
    && ./configure \
    && make -j"$(nproc)" \
    && make install \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/phpredis /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
