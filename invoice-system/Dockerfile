FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN mkdir -p /app/data && chmod -R 777 /app/data

RUN chmod -R 777 /app/data

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000"]
