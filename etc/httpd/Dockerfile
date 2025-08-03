FROM httpd:2.4.65-alpine
LABEL authors="Timur Davydov"

COPY ./www /usr/local/apache2/htdocs
COPY ./etc/httpd/httpd.conf /usr/local/apache2/conf/httpd.conf

RUN chown -R www-data:www-data /usr/local/apache2/htdocs \
    && find /usr/local/apache2/htdocs -type f -exec chmod 644 {} \; \
    && find /usr/local/apache2/htdocs -type d -exec chmod 755 {} \; \
    && chmod -R 775 /usr/local/apache2/htdocs/storage

CMD ["httpd-foreground"]