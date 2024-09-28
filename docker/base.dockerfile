FROM php:8.3.6-fpm

ENV DEBIAN_FRONTEND=noninteractive
WORKDIR /app

RUN apt-get update -y \
    && apt-get install --no-install-recommends -y \
        wget dnsutils iputils-ping telnet procps busybox gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3  \
        libfreetype6-dev \
        libicu-dev \
        libjpeg-dev \
        libmagickwand-dev \
        libpng-dev \
        libwebp-dev \
        libzip-dev \
        libcap2-bin \
        libpng-dev \
        libgmp-dev \
        libsodium-dev \
        libuv1-dev \
        ffmpeg \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j "$(nproc)" bcmath exif gd gmp intl mysqli pcntl pdo_mysql zip \
    && pecl install -f redis uv sodium --with-maximum-processors="$(nproc)" \
    && docker-php-ext-enable redis \
    && apt-get purge -y --auto-remove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -L -o /tmp/imagick.tar.gz https://github.com/Imagick/imagick/archive/7088edc353f53c4bc644573a79cdcd67a726ae16.tar.gz \
    && tar --strip-components=1 -xf /tmp/imagick.tar.gz \
    && phpize \
    && ./configure \
    && make \
    && make install \
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini \
    && rm -rf /tmp/*

RUN groupadd --force -g 1000 raft \
    && useradd -ms /bin/bash --no-user-group -g raft -u 1000 raft \
    && chown -R raft:raft /app && chmod -R 755 /app

COPY confs/php/ini/* /usr/local/etc/php/conf.d/
COPY confs/php/fpm/* /usr/local/etc/php-fpm.d/
COPY confs/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY confs/entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
