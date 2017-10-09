FROM php:7

RUN php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

RUN apt-get update && \
    apt-get install -yqq git && \
    git config --global user.email "test@gitelephant.org" && \
    git config --global user.name "GitElephant tests" && \
    rm -rf /var/lib/apt/lists/*

RUN apt-get update && \
    apt-get install -yqq zlib1g-dev && \
    docker-php-ext-install zip && \
    rm -rf /var/lib/apt/lists/*
