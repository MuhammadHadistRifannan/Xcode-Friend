#!/bin/sh
set -u

cd /var/www/html || exit 1

log() { echo "[entrypoint] $*"; }

# --- Tunable via environment (defaults safe) ---
ENABLE_REVERB="${ENABLE_REVERB:-1}"
ENABLE_QUEUE="${ENABLE_QUEUE:-1}"
ENABLE_SCHEDULER="${ENABLE_SCHEDULER:-1}"
QUEUE_WORKERS="${QUEUE_WORKERS:-1}"
RUN_MIGRATIONS="${RUN_MIGRATIONS:-0}"

DB_HOST="${DB_HOST:-db}"
DB_PORT="${DB_PORT:-3306}"
DB_USERNAME="${DB_USERNAME:-root}"
DB_PASSWORD="${DB_PASSWORD:-}"

SERVICES=""

# --- Wait until the database accepts connections (max ~120s) ---
wait_db() {
    log "waiting for database ${DB_HOST}:${DB_PORT}..."
    i=0
    until mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" --password="$DB_PASSWORD" --skip-ssl --silent >/dev/null 2>&1; do
        i=$((i + 1))
        if [ "$i" -ge 60 ]; then
            log "database not reachable after ~120s; continuing anyway"
            return 1
        fi
        sleep 2
    done
    log "database is reachable"
}

# --- Run a long-lived command in background, restart it if it dies ---
spawn() {
    name="$1"
    shift
    (
        while :; do
            "$@" 2>&1
            code=$?
            log "$name stopped (exit $code); restarting in 3s..."
            sleep 3
        done
    ) &
    SERVICES="$SERVICES $!"
    log "spawned $name"
}

start_services() {
    # One-time setup
    if [ ! -L public/storage ] && [ ! -e public/storage ]; then
        php artisan storage:link >/dev/null 2>&1 && log "storage:link OK" || log "storage:link skipped/failed"
    fi

    # Optional migrations (wait for DB first)
    if [ "$RUN_MIGRATIONS" = "1" ]; then
        if wait_db; then
            php artisan migrate --force && log "migrations applied" || log "migrate failed"
        fi
    else
        wait_db || true
    fi

    # Long-running services, all in parallel
    if [ "$ENABLE_REVERB" = "1" ]; then
        spawn reverb php artisan reverb:start --host=0.0.0.0 --port=8080
    fi

    if [ "$ENABLE_QUEUE" = "1" ]; then
        n=1
        while [ "$n" -le "$QUEUE_WORKERS" ]; do
            spawn "queue:$n" php artisan queue:work --sleep=3 --tries=5 --timeout=90
            n=$((n + 1))
        done
    fi

    if [ "$ENABLE_SCHEDULER" = "1" ]; then
        spawn scheduler php artisan schedule:work --verbose
    fi
}

_term() {
    log "shutting down services..."
    kill -TERM $SERVICES 2>/dev/null
    sleep 1
    exit 0
}
trap _term TERM INT

start_services

log "starting php-fpm..."
php-fpm

kill -TERM $SERVICES 2>/dev/null
_term