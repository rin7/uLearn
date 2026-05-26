FROM php:5.6-apache

# Install the legacy mysql extension (not mysqli)
RUN docker-php-ext-install mysql mysqli

# Enable Apache mod_rewrite (in case needed)
RUN a2enmod rewrite

# Set the document root to /var/www/html/ulearn
# But we'll mount the full ulearn folder, so Apache serves from /var/www/html
# and the app lives at /ulearn/ path matching the original structure

# Configure PHP for legacy compatibility
RUN echo "short_open_tag = On" >> /usr/local/etc/php/php.ini-development \
    && echo "display_errors = On" >> /usr/local/etc/php/php.ini-development \
    && echo "error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED" >> /usr/local/etc/php/php.ini-development \
    && echo "session.auto_start = 0" >> /usr/local/etc/php/php.ini-development \
    && cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

EXPOSE 80
