FROM nginx:1.29-alpine3.23

RUN apk add --no-cache curl

COPY ./common/nginx/conf.d /etc/nginx/conf.d

COPY ./common/nginx/docker-entrypoint-nginx-conf.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint-nginx-conf.sh

ENTRYPOINT ["docker-entrypoint-nginx-conf.sh", "/docker-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]

WORKDIR /app

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s CMD curl --fail http://127.0.0.1/health || exit 1
