FROM node:24-alpine3.21 AS builder

ENV VITE_AUTH_URL=%%VITE_AUTH_URL%%

WORKDIR /app

COPY ./package.json ./yarn.lock ./
RUN yarn install && yarn cache clean

COPY ./ ./
RUN yarn build

FROM nginx:1.29-alpine3.23

RUN apk add --no-cache curl

COPY ./docker/production/nginx/conf.d /etc/nginx/conf.d

COPY ./docker/common/nginx/docker-entrypoint-nginx-conf.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint-nginx-conf.sh

COPY ./docker/production/nginx/docker-entrypoint-subst.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint-subst.sh

ENTRYPOINT ["docker-entrypoint-nginx-conf.sh", "docker-entrypoint-subst.sh", "/docker-entrypoint.sh"]
CMD ["nginx", "-g", "daemon off;"]

WORKDIR /app

COPY --from=builder /app/dist ./public

HEALTHCHECK --interval=5s --timeout=3s --start-period=1s CMD curl --fail http://127.0.0.1/health || exit 1
