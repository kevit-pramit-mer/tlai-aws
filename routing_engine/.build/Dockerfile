# Use an appropriate base image, e.g., Debian 10
FROM debian:buster
USER root
# Set environment variables
ENV DEBIAN_FRONTEND noninteractive
RUN for i in $(seq 1 8); do mkdir -p "/usr/share/man/man${i}"; done \
        && apt update && apt -y --quiet --allow-remove-essential upgrade \
        && apt install -y --quiet --no-install-recommends vim gnupg2 apt-utils wget curl git lsb-release cmake libpcap-dev libncurses5-dev libgsl-dev automake autoconf libtool libtool-bin build-essential pkg-config ca-certificates  libswscale-dev libldns-dev libmp3lame-dev nano net-tools unixodbc unixodbc-dev tcpdump procps \ 
        && apt install -y lua5.2 lua5.2-dev lua-json libssl-dev luarocks librabbitmq-dev libtiff5-dev \
        && apt install -y build-essential libavformat-dev libpq-dev libswscale-dev python3-pip php libapache2-mod-php librabbitmq-dev pkg-config uuid-dev zlib1g-dev libjpeg-dev libsqlite3-dev libcurl4-openssl-dev libpcre3-dev libspeexdsp-dev libldns-dev libedit-dev libtiff5-dev yasm libopus-dev libsndfile1-dev sngrep vim dnsutils ncat default-mysql-client curl jq sqlite3 unixodbc libfreetype6 libcurl4-openssl-dev libedit2 libsndfile1 wget iputils-ping awscli xmlstarlet --no-install-recommends


# Install required dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    autoconf \
    automake \
    g++ \
    git \
    libjpeg-dev \
    libncurses5-dev \
    libtool \
    libssl-dev \
    libcurl4-openssl-dev \
    libpcre3-dev \
    libldns-dev \
    libedit-dev \
    libtiff5-dev \
    libopus-dev \
    libvpx-dev \
    libspeexdsp-dev \
    unixodbc-dev \
    libpq-dev \
    libsqlite3-dev \
    libperl-dev \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libavformat-dev \
    libavcodec-dev \
    libavutil-dev \
    libavdevice-dev \
    libavfilter-dev \
    libjpeg62-turbo-dev \
    yasm \
    libspandsp-dev \
    liblua5.3-dev \
    lua5.3 \
    lua5.3-json \
    libssl-dev \
    luarocks \
    wget \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
# Download and install libtool version 2.4.6
RUN apt-get update && \
    apt-get -y install sudo libmariadb-dev
RUN cd /usr/src && \
    wget https://ftp.gnu.org/gnu/libtool/libtool-2.4.6.tar.gz && \
    tar -xzvf libtool-2.4.6.tar.gz && \
    cd libtool-2.4.6 && \
    ./configure && \
    make && \
    make install
RUN cd /usr/src && \
    git clone https://github.com/freeswitch/spandsp.git && \
    cd spandsp && \
    ./bootstrap.sh -j && \
    ./configure && \
    make && make install && \
   sudo ldconfig

RUN cd /usr/src && \
    git clone https://github.com/freeswitch/sofia-sip && \
    cd sofia-sip && \
    ./bootstrap.sh -j && \
    ./configure && \
    make && make install && \
   sudo ldconfig
RUN  apt install -y cmake python3-distutils 
RUN cd /usr/src && \
      git clone https://github.com/signalwire/libks.git && \
      cd libks && \
#      mv src/include/libks && \
      sudo cmake . && \
      make && make install && \
      mkdir -p /usr/include && \
      mv src/include/libks /usr/include/

RUN cd /usr/src && \
     git clone https://github.com/signalwire/signalwire-c.git && \
     cd signalwire-c && \
     sudo cmake .
RUN cd /usr/src/ && \
     apt-get install -y libxml2-* && \
     apt-get install -y libbson-dev && \
     apt-get install -y libbson-1.0-0 && \
     apt-get install -y cmake libssl-dev libsasl2-dev && \
     git clone https://github.com/mongodb/mongo-c-driver.git && \
     cd mongo-c-driver && \
     git checkout 1.6.3 && \
      mkdir cmake-build && \
     cd cmake-build && \
     cmake -DENABLE_AUTOMATIC_INIT_AND_CLEANUP=OFF .. 

RUN cd /usr/src/
RUN apt-get install -y php-dev ca-certificates-java openjdk-11-jre-headless erlang libmongoc-dev libhiredis-dev libshout3-dev libmpg123-dev
RUN cp /usr/include/libbson-1.0/bson.h /usr/local/include/ && \
    cp -r /usr/include/libbson-1.0/bson /usr/local/include/ && \
    cp /usr/include/libmongoc-1.0/mongoc.h /usr/local/include/ && \
    cp -r /usr/include/libmongoc-1.0/mongoc/* /usr/local/include/ && \
     cp -r /usr/include/libmongoc-1.0/mongoc /usr/local/include/


RUN mkdir -p /usr/src/jansson-2.10
RUN mkdir -p /usr/src/routing_engine
 
COPY ./jansson-2.10 /usr/src/jansson-2.10/
COPY ./routing_engine /usr/src/routing_engine/
WORKDIR /usr/src/jansson-2.10/
RUN ls -lah
RUN ./configure --libdir=/usr/lib64 && \
     autoreconf -f -i && \
     make && \
     make install
RUN apt-get install -y libhiredis-dev libconfig++-dev libjansson-dev
RUN cp /usr/include/libbson-1.0/bson/bcon.h /usr/local/include/
WORKDIR  /usr/src/routing_engine/
RUN make && \
   make install

RUN mkdir -p /var/log/routing_engine && \
          chmod -R 755 /var/log/routing_engine && \
          touch /var/log/routing_engine/routing.log && \
          cp routingengine.ini /etc/ && \
          cp bin/routing_engine /usr/bin/ && \
          cp routing.service /etc/systemd/system/
RUN mkdir -p  /usr/lib/odbc/ 
RUN cd /usr/lib/odbc/ && \
   wget https://cdn.mysql.com/archives/mysql-connector-odbc-8.0/mysql-connector-odbc-8.0.23-linux-glibc2.12-x86-64bit.tar.gz && \
    tar xvf mysql-connector-odbc-8.0.23-linux-glibc2.12-x86-64bit.tar.gz && \
     mv mysql-connector-odbc-8.0.23-linux-glibc2.12-x86-64bit/lib/* /usr/lib/odbc/ && \
     rm -rf mysql-connector-odbc-8.0.23-linux-glibc2.12-x86-64bit*
COPY ./libmaodbc.so /usr/lib/

COPY ./run.sh /run.sh
RUN chmod -R 777 /run.sh
EXPOSE 5062 5062/udp 5063 5063/udp 5080 5080/udp 5081 7443 8021 8081 8082
ENTRYPOINT ["/bin/sh", "/run.sh"]

