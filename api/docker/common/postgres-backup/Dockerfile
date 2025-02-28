FROM alpine:3.23

RUN apk add --no-cache bash coreutils postgresql-client aws-cli

RUN addgroup -g 1000 app && adduser -u 1000 -G app -s /bin/sh -D app

COPY ./wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

COPY ./postgres-backup/backup.sh /usr/local/bin/backup
RUN chmod 555 /usr/local/bin/backup

CMD ["backup"]

WORKDIR /app

RUN chown app:app /app

USER app
