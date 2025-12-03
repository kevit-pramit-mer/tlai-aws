## Dummy Dockerfile ####
FROM debian:11.10-slim

RUN apt-get update && apt-get install -qqy software-properties-common wget gpg vim unzip zip libjpeg62-turbo-dev libpng-dev supervisor curl gnupg2 ca-certificates lsb-release apt-transport-https \
    && echo "deb http://nginx.org/packages/mainline/debian $(lsb_release -cs) nginx" >> /etc/apt/sources.list.d/nginx.list \
    && curl -fsSL http://nginx.org/keys/nginx_signing.key | apt-key add - \
    && apt-get update -y \
    && apt-get install -y nginx

RUN wget --no-check-certificate -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
RUN apt-get update -y
RUN apt install -y php8.2 php8.2-cli php8.2-common php8.2-fpm php8.2-bcmath php8.2-curl php8.2-dom php8.2-gd php8.2-imagick php8.2-imap php8.2-intl php8.2-mbstring php8.2-ldap php8.2-mongodb php8.2-mysqli php8.2-mysqlnd php8.2-pdo php8.2-SimpleXML php8.2-tidy php8.2-xml php8.2-xmlreader php8.2-xmlrpc php8.2-xmlwriter php8.2-xsl php8.2-zip php8.2-dev php8.2-xml php8.2-curl php8.2-dom



RUN apt-get install -y git autoconf pkg-config libssl-dev

RUN cd /usr/share/nginx/html/
RUN mkdir -p /usr/share/nginx/html/uctenant
COPY ./ /usr/share/nginx/html/uctenant/
RUN cd /usr/share/nginx/html/uctenant

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /usr/share/nginx/html/uctenant
RUN composer install --ignore-platform-reqs
RUN chown -R  www-data:www-data  /usr/share/nginx/html/uctenant
RUN apt-get install -y cron
EXPOSE 80 443

WORKDIR /usr/share/nginx/html/uctenant/config
RUN mv params.example.php params.php
RUN mv mongo.example.php mongo.php
RUN mv db.example.php db.php
WORKDIR /usr/share/nginx/html/uctenant/
RUN mkdir -p web/assets
RUN chown -R www-data:www-data web/assets
Run mkdir -p web/media
RUN chown -R www-data:www-data web/media/
RUN chmod -R 777 web/media/

RUN apt-get install  tcpdump mariadb-client sudo -y
RUN apt-get install -y gnupg curl  sudo

RUN mkdir -p /run/php/
#max upload size and post data

RUN sed -i 's/upload_max_filesize\s*=.*/upload_max_filesize = 100M/g' /etc/php/8.2/cli/php.ini
RUN sed -i 's/post_max_size\s*=.*/post_max_size = 100M/g' /etc/php/8.2/cli/php.ini
RUN sed -i 's/upload_max_filesize\s*=.*/upload_max_filesize = 100M/g' /etc/php/8.2/fpm/php.ini
RUN sed -i 's/post_max_size\s*=.*/post_max_size = 100M/g' /etc/php/8.2/fpm/php.ini
RUN sed -i 's/;max_input_vars = 100000000\s*=.*/max_input_vars = 100000000/g' /etc/php/8.2/cli/php.ini
RUN sed -i 's/;max_input_vars = 100000000\s*=.*/max_input_vars = 100000000/g' /etc/php/8.2/fpm/php.ini
RUN sed -i 's/memory_limit\s*=.*/memory_limit = -1/g' /etc/php/8.2/fpm/php.ini

RUN sed -i 's/user  nginx/user  www-data/g' /etc/nginx/nginx.conf

COPY ./supervisord.conf /etc/supervisor/supervisord.conf
RUN chmod 755 /etc/supervisor/supervisord.conf
RUN touch /opt/privkey.pem
RUN touch /opt/fullchain.pem

COPY ./docker/eco-cron /etc/cron.d/eco-cron
RUN chmod 0644 /etc/cron.d/eco-cron
RUN crontab /etc/cron.d/eco-cron
RUN mkdir -p /var/log/cron

CMD ["/usr/bin/supervisord"]

