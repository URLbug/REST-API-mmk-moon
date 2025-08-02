FROM httpd:2.4.65-alpine
LABEL authors="Timur Davydov"

COPY ./www /usr/local/apache2/htdocs
COPY ./etc/httpd/httpd.conf /usr/local/apache2/conf/httpd.conf

CMD ["httpd", "-D", "FOREGROUND"]