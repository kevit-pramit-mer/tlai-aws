FROM debian:bullseye-slim 

RUN apt-get update && apt-get install -qqy software-properties-common wget gpg vim unzip zip libjpeg62-turbo-dev libpng-dev nginx supervisor curl gnupg2 ca-certificates lsb-release apt-transport-https sudo

RUN wget --no-check-certificate -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
RUN apt-get update -y

RUN apt install -y php7.3 php7.3-cli php7.3-common  php7.3-fpm php7.3-bcmath php7.3-curl php7.3-dom php7.3-gd php7.3-imagick php7.3-imap  php7.3-intl php7.3-mbstring php7.3-ldap  php7.3-mongodb php7.3-mysqli php7.3-mysqlnd php7.3-pdo php7.3-recode php7.3-SimpleXML php7.3-tidy php7.3-wddx php7.3-xml php7.3-xmlreader php7.3-xmlrpc php7.3-xmlwriter php7.3-xsl php7.3-zip php7.3-dev php7.3-xml php7.3-curl php7.3-dom nginx

RUN apt-get install -y autoconf pkg-config libssl-dev
RUN apt-get install -y libmongoc*

RUN apt-get install wget git libc6-i386 lib32stdc++6 -y

RUN mkdir -p /usr/share/nginx/html/fs_curl

WORKDIR /usr/share/nginx/html/fs_curl/
COPY . /usr/share/nginx/html/fs_curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --ignore-platform-reqs
WORKDIR /usr/share/nginx/html/fs_curl/libs/
RUN composer install
RUN chown -R  www-data:www-data  /usr/share/nginx/html/fs_curl
RUN touch /var/log/fs_curl.log
RUN chmod 777 /var/log/fs_curl.log
RUN mkdir -p /run/php/
EXPOSE 81
WORKDIR /usr/share/nginx/html/fs_curl/
COPY ./supervisord.conf /etc/supervisor/supervisord.conf
RUN chmod 755 /etc/supervisor/supervisord.conf
RUN echo "" > /etc/nginx/sites-available/default
CMD ["/usr/bin/supervisord"]

