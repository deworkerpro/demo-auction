#!/bin/sh
set -e

find /etc/nginx \
  -type f \
  -name '*.conf' \
  -exec sed -i "s+%%NGINX_UPSTREAM_RESOLVER%%+${NGINX_UPSTREAM_RESOLVER:?}+g" '{}' \; \
  -exec sed -i "s+%%NGINX_UPSTREAM_HOST%%+${NGINX_UPSTREAM_HOST}+g" '{}' \;

exec "$@"
