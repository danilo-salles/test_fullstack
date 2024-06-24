# Use a imagem oficial do PHP com Apache baseada no Debian
FROM php:8.1-apache

# Comentado para evitar problemas de dependências
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    cron \
    libzip-dev \
    unzip && \
    docker-php-ext-configure gd --with-jpeg --with-freetype && \
    docker-php-ext-install -j$(nproc) \
    intl \
    zip \
    pdo_mysql \
    gd

# # Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

#diretorio do prj
WORKDIR /var/www/html

# # Configurar o Apache para Laravel
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# # Copiar arquivos do aplicativo Laravel para o diretório web do Apache
COPY ./app /var/www/html

##composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update

##! TERMINAR

#RUN composer install --no-dev --optimize-autoloader

#RUN php artisan migrate

# #permissões
# RUN chown -R $USER:www-data .
# RUN find . -type f -exec chmod 664 {} \;   
# RUN find . -type d -exec chmod 775 {} \;
# RUN chgrp -R www-data storage bootstrap/cache
# RUN chmod -R ug+rwx storage bootstrap/cache
#RUN chown -R www-data:www-data /var/www/html

# # Expor porta 80 do container
EXPOSE 8000

# # Comando padrão para iniciar o Apache em foreground

CMD ["apache2-foreground"]