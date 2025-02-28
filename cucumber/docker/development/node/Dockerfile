FROM node:21-alpine3.19

RUN apk add --no-cache \
    chromium \
    nss \
    freetype \
    freetype-dev \
    harfbuzz \
    ca-certificates \
    ttf-freefont

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium-browser

RUN apk add --no-cache shadow

ARG UID=1000
ARG GID=1000

RUN groupmod -g "${GID}" node && usermod -u "${UID}" node && chown node:node /home/node

WORKDIR /app

USER node
