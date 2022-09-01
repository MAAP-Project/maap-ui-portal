FROM wordpress:6.0.2-php7.4-apache
LABEL maintainer="support@maap-project.org"
LABEL version="0.0.1"

# THIS COPY CODE IS NOT WORKING!
WORKDIR /usr/src/wordpress/wp-content
COPY ./plugins plugins
COPY ./themes themes
RUN mkdir uploads
COPY ./uploads uploads

EXPOSE 80

WORKDIR /var/www/html/