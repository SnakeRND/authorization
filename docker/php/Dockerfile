FROM --platform=linux/amd64 alpine:3.16 as builder
RUN apk update
RUN apk add php81 php81-dev php81-pear php81-pdo php81-openssl autoconf make g++ unixodbc-dev
RUN ln -s /usr/bin/php81 /usr/bin/php
RUN ln -s /usr/bin/phpize81 /usr/bin/phpize
RUN ln -s /usr/bin/pecl81 /usr/bin/pecl
RUN ln -s /usr/bin/php-config81 /usr/bin/php-config
RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv


FROM --platform=linux/amd64 alpine:3.16

ARG WWWGROUP

WORKDIR /var/www/
RUN echo "UTC" > /etc/timezone
RUN apk update
RUN apk add --no-cache zip unzip curl sqlite nginx supervisor unixodbc
# Installing bash
RUN apk add bash
RUN sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd
# Installing PHP
RUN apk add --no-cache php81 \
    php81-common \
    php81-fpm \
    php81-pdo \
    php81-opcache \
    php81-zip \
    php81-phar \
    php81-iconv \
    php81-cli \
    php81-curl \
    php81-openssl \
    php81-mbstring \
    php81-tokenizer \
    php81-fileinfo \
    php81-json \
    php81-xml \
    php81-xmlwriter \
    php81-simplexml \
    php81-dom \
    php81-pdo_pgsql \
    php81-pdo_sqlite \
    php81-tokenizer \
    php81-pecl-redis \
    php81-redis \
    php81-gd \
    php81-pecl-xdebug

RUN ln -s /usr/bin/php81 /usr/bin/php
RUN ln -s /usr/bin/phpize81 /usr/bin/phpize
RUN ln -s /usr/bin/php-config81 /usr/bin/php-config

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

# Microsoft ODBC driver
#Download the desired package(s)
RUN curl -O https://download.microsoft.com/download/b/9/f/b9f3cce4-3925-46d4-9f46-da08869c6486/msodbcsql18_18.1.1.1-1_amd64.apk
RUN curl -O https://download.microsoft.com/download/b/9/f/b9f3cce4-3925-46d4-9f46-da08869c6486/mssql-tools18_18.1.1.1-1_amd64.apk
#(Optional) Verify signature, if 'gpg' is missing install it using 'apk add gnupg':
RUN apk add gnupg
RUN curl -O https://download.microsoft.com/download/b/9/f/b9f3cce4-3925-46d4-9f46-da08869c6486/msodbcsql18_18.1.1.1-1_amd64.sig
RUN curl -O https://download.microsoft.com/download/b/9/f/b9f3cce4-3925-46d4-9f46-da08869c6486/mssql-tools18_18.1.1.1-1_amd64.sig
RUN curl https://packages.microsoft.com/keys/microsoft.asc  | gpg --import -
RUN gpg --verify msodbcsql18_18.1.1.1-1_amd64.sig msodbcsql18_18.1.1.1-1_amd64.apk
RUN gpg --verify mssql-tools18_18.1.1.1-1_amd64.sig mssql-tools18_18.1.1.1-1_amd64.apk
#Install the package(s)
RUN apk add --allow-untrusted msodbcsql18_18.1.1.1-1_amd64.apk
RUN apk add --allow-untrusted mssql-tools18_18.1.1.1-1_amd64.apk
# sqlsrv
COPY --from=builder /usr/lib/php81/modules/sqlsrv* /usr/lib/php81/modules/
COPY --from=builder /usr/lib/php81/modules/pdo_sqlsrv* /usr/lib/php81/modules/
RUN echo extension=pdo_sqlsrv.so >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/10_pdo_sqlsrv.ini
RUN echo extension=sqlsrv.so >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/20_sqlsrv.ini

# Configure supervisor
RUN mkdir -p /etc/supervisor.d/
COPY ./supervisord.ini /etc/supervisor.d/supervisord.ini

# Configure PHP
RUN mkdir -p /run/php/
RUN touch /run/php/php8.1-fpm.pid
RUN sed -i "s/memory_limit = 128M/memory_limit = 512M/g" /etc/php81/php.ini

COPY ./php-fpm.conf /etc/php81/php-fpm.conf
COPY ./php.ini-production /etc/php81/php.ini
COPY ./www.conf /etc/php81/php-fpm.d/www.conf

# Configure nginx
COPY ./nginx.conf /etc/nginx/
RUN rm /etc/nginx/http.d/default.conf
COPY ./nginx-laravel.conf /etc/nginx/http.d/

RUN mkdir -p /run/nginx/
RUN touch /run/nginx/nginx.pid

RUN addgroup -g 1000 sail \
    && adduser -G sail -u 1000 sail -D \
    && adduser nginx sail

EXPOSE 80
CMD ["supervisord", "-c", "/etc/supervisor.d/supervisord.ini"]
