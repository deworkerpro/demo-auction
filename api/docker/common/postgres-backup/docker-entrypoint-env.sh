#!/bin/sh

set -o errexit

if [ -f "$POSTGRES_PASSWORD_FILE" ]; then
  export POSTGRES_PASSWORD="$(cat "$POSTGRES_PASSWORD_FILE")"
  unset POSTGRES_PASSWORD_FILE
fi

if [ -f "$AWS_SECRET_ACCESS_KEY_FILE" ]; then
  export AWS_SECRET_ACCESS_KEY="$(cat "$AWS_SECRET_ACCESS_KEY_FILE")"
  unset AWS_SECRET_ACCESS_KEY_FILE
fi

exec "$@"
