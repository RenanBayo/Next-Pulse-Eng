FROM php:8.1-cli
WORKDIR /var/www/html
COPY hpdv/backend             # <-- muda aqui
EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:10000"]
