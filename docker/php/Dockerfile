FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        $PHPIZE_DEPS \
        git \
        unzip \
    && rm -rf /var/lib/apt/lists/*

RUN git clone --depth 1 --branch 6.3.0 https://github.com/phpredis/phpredis.git /tmp/phpredis

WORKDIR /tmp/phpredis
RUN phpize \
    && ./configure \
    && make -j"$(nproc)" \
    && make install \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/phpredis

WORKDIR /app