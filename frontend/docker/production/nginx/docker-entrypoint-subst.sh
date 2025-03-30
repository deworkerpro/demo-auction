#!/bin/sh

set -o errexit

find /app/public \
  -type f \
  -name '*.js' \
  -exec sed -i "s+%%VITE_AUTH_URL%%+${VITE_AUTH_URL:?}+g" '{}' \;

exec "$@"
