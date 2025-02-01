FROM alpine:3.21

RUN apk add --no-cache python3 py3-pip bash coreutils postgresql-client \
    && pip3 install --upgrade --break-system-packages awscli

COPY ./wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

COPY ./postgres-backup/backup.sh /usr/local/bin/backup
RUN chmod 555 /usr/local/bin/backup

WORKDIR /app

CMD ["backup"]
