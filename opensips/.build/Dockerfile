FROM debian:buster

USER root

# Set Environment Variables
ENV DEBIAN_FRONTEND noninteractive

ARG OPENSIPS_VERSION=3.4
ARG OPENSIPS_BUILD=releases

RUN sed -i -e '$adeb http://ftp.de.debian.org/debian buster main non-free' /etc/apt/sources.list
RUN apt-get update  -y
RUN apt-get -y  --quiet --force-yes upgrade curl iproute2 \
  && apt-get install -y --no-install-recommends ca-certificates gcc g++ make build-essential git iptables-dev libavfilter-dev \
  libevent-dev libpcap-dev libxmlrpc-core-c3-dev markdown \
  libjson-glib-dev default-libmysqlclient-dev libhiredis-dev libssl-dev \
  libcurl4-openssl-dev libavcodec-extra gperf libspandsp-dev libwebsockets-dev flex bison bash libncurses5-dev m4 libmicrohttpd-dev locate git sed wget tar libjson-c-dev libsnmp-dev php php-curl redis-tools snmp snmp-mibs-downloader librabbitmq-dev python3 python3-pip python3-dev gcc default-libmysqlclient-dev python3-mysqldb python3-sqlalchemy python3-sqlalchemy-utils python3-openssl openssl
RUN pip3 install mysqlclient sqlalchemy sqlalchemy-utils pyOpenSSL

RUN cd /usr/local/src && git clone https://github.com/OpenSIPS/opensips.git && cd /usr/local/src/opensips && git checkout 3.4.2 

WORKDIR /usr/local/src/opensips
RUN make && make install

RUN make install include_modules="db_mysql" \ 
&& make install include_modules="signaling" \
&& make install include_modules="sl" \ 
&& make install include_modules="tm" \ 
&& make install include_modules="sipmsgops" \
&& make install include_modules="uac_auth" \ 
&& make install include_modules="uac_registrant" \
&& make install include_modules="permissions" \ 
&& make install include_modules="rabbitmq_consumer" \ 
&& make install include_modules="mi_fifo" \
&& make install include_modules="exec" \
&& make install include_modules="event_route" \
&& make install include_modules="clusterer" \
&& make install include_modules="proto_udp" \
&& make install include_modules="proto_tcp" \
&& make install include_modules="proto_ws" \
&& make install include_modules="proto_wss" \
&& make install include_modules="proto_tls" \
&& make install include_modules="rr" \
&& make install include_modules="usrloc" \
&& make install include_modules="dialog" \
&& make install include_modules="uac" \
&& make install include_modules="regex" \
&& make install include_modules="avpops" \
&& make install include_modules="nathelper" \
&& make install include_modules="tls_openssl" \
&& make install include_modules="tls_mgm" \
&& make install include_modules="maxfwd" \
&& make install include_modules="rtpengine" \
&& make install include_modules="cfgutils" \
&& make install include_modules="mid_registrar" \
&& make install include_modules="registrar" \
&& make install include_modules="nat_traversal" \
&& make install include_modules="proto_hep" \
&& make install include_modules="tracer"

COPY ./opensips.cfg /usr/local/etc/opensips/opensips.cfg
EXPOSE 5060/udp 5060/tcp
COPY ./opensips-cli.cfg /etc/opensips-cli.cfg
#RUN ls -lah /usr/local/etc/opensips/cert/
RUN cp /usr/local/src/opensips/modules/snmpstats/mibs/* /usr/share/snmp/mibs/ietf/
COPY ./run.sh /run.sh
RUN chmod +x /run.sh

EXPOSE 5060 5060/udp 5063 5065 
RUN git clone https://github.com/opensips/opensips-cli /src/opensips-cli
WORKDIR /src/opensips-cli
RUN chmod 777 setup.py
RUN python3 setup.py install --user clean
RUN python3 setup.py install clean


ENTRYPOINT ["/run.sh"]

