FROM php:8.2-apache

# Extensions PHP nécessaires pour ce projet (MySQLi + PDO MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Active mod_rewrite (utile si vous ajoutez des routes réécrites plus tard)
RUN a2enmod rewrite

# Copie du projet dans le répertoire web Apache
WORKDIR /var/www/html
COPY . /var/www/html

# Script de démarrage: Render impose un port dynamique via $PORT
COPY docker/entrypoint.sh /usr/local/bin/render-entrypoint.sh
RUN chmod +x /usr/local/bin/render-entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["render-entrypoint.sh"]
CMD ["apache2-foreground"]
