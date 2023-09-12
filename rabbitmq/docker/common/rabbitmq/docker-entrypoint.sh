#!/usr/bin/env bash
set -euo pipefail

if [ -f "$RABBITMQ_DEFAULT_PASS_FILE" ]; then
  export RABBITMQ_DEFAULT_PASS="$(cat "$RABBITMQ_DEFAULT_PASS_FILE")"
fi

# allow the container to be started with `--user`
if [[ "$1" == rabbitmq* ]] && [ "$(id -u)" = '0' ]; then
	if [ "$1" = 'rabbitmq-server' ]; then
		find /var/lib/rabbitmq \! -user rabbitmq -exec chown rabbitmq '{}' +
	fi

	exec su-exec rabbitmq "$BASH_SOURCE" "$@"
fi

# if long and short hostnames are not the same, use long hostnames
if [ -z "${RABBITMQ_USE_LONGNAME:-}" ] && [ "$(hostname)" != "$(hostname -s)" ]; then
	: "${RABBITMQ_USE_LONGNAME:=true}"
fi

exec "$@"
