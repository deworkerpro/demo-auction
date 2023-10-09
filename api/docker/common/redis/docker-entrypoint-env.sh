#!/bin/sh
set -e

if [ -f "$REDIS_PASSWORD_FILE" ]; then
  REDIS_PASSWORD="$(cat $REDIS_PASSWORD_FILE)"
fi

if [ ! -z "$REDIS_PASSWORD" ]; then
  echo "user default on >$REDIS_PASSWORD ~* &* +@all" > /etc/users.acl
  set -- "$@" --aclfile /etc/users.acl
fi

exec docker-entrypoint.sh "$@"
