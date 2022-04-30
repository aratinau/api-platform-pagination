
ARG NGINX_VERSION=1.17

FROM nginx:${NGINX_VERSION}-alpine

RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk --no-cache add shadow && usermod -u 1000 nginx