#!/bin/sh

set -o errexit

HOST_DOMAIN="host.docker.internal"
if ! ping -q -c1 $HOST_DOMAIN > /dev/null 2>&1
then
  HOST_IP=$(ip route | awk 'NR==1 {print $3}')
  # shellcheck disable=SC2039
  echo -e "$HOST_IP\t$HOST_DOMAIN" >> /etc/hosts
fi

exec "$@"
