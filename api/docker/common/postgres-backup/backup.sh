#!/bin/bash

set -o errexit
set -o pipefail

if [ -f "$POSTGRES_PASSWORD_FILE" ]; then
  POSTGRES_PASSWORD="$(cat "$POSTGRES_PASSWORD_FILE")"
fi

if [ -f "$AWS_SECRET_ACCESS_KEY_FILE" ]; then
  AWS_SECRET_ACCESS_KEY="$(cat "$AWS_SECRET_ACCESS_KEY_FILE")"
fi

BACKUP_FILE="${BACKUP_NAME:?}_$(date +%Y-%m-%d_%H-%M).sql.gz"

echo "Dump $BACKUP_FILE"

export PGPASSWORD="${POSTGRES_PASSWORD:?}"

pg_dump \
    --dbname="${POSTGRES_DB:?}" \
    --username="${POSTGRES_USERNAME:?}" \
    --host="${POSTGRES_HOST:?}" \
    | gzip -9 > "$BACKUP_FILE"

echo "Upload to S3"

export AWS_ACCESS_KEY_ID="${AWS_ACCESS_KEY_ID:?}"
export AWS_SECRET_ACCESS_KEY="${AWS_SECRET_ACCESS_KEY:?}"
export AWS_DEFAULT_REGION="${AWS_DEFAULT_REGION:?}"
export AWS_ENDPOINT_URL="${S3_ENDPOINT:?}"

aws s3 cp "$BACKUP_FILE" "s3://${S3_BUCKET:?}/$BACKUP_FILE"

unlink "$BACKUP_FILE"
