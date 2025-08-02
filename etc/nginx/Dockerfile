FROM nginx:stable-alpine
LABEL authors="Timur Davydov"

COPY ./etc/nginx/default.conf /etc/nginx/conf.d/

CMD ["nginx", "-g", "daemon off;"]