FROM php:8.1.6-cli

RUN apt-get update && \
    apt-get install zip unzip && \
    rm -rf /var/lib/apt/lists/*

RUN apt-get update && \
    apt-get install -y git && \
    rm -rf /var/lib/apt/lists/*	

RUN pecl install mongodb && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable mongodb
