FROM node:24-alpine3.21

RUN apk add --no-cache shadow

ARG UID=1000
ARG GID=1000

RUN groupmod -g "${GID}" node && usermod -u "${UID}" node && chown node:node /home/node

RUN apk add --no-cache curl

WORKDIR /app

USER node
