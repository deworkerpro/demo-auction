FROM node:15-alpine

RUN apk add --no-cache curl

WORKDIR /app

HEALTHCHECK --interval=5s --timeout=5s --start-period=1s CMD curl --fail http://127.0.0.1:3000/health || exit 1
