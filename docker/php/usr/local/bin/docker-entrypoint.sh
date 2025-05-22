#!/bin/sh

set -e

case "${1:-}" in
    "optimize")
        exec /bin/sh -c 'php artisan route:cache && php artisan config:cache && php artisan view:cache && php artisan event:cache'
        ;;
    "laravel-app")
        exec /bin/sh -c '/usr/local/bin/docker-entrypoint.sh optimize && /usr/local/bin/docker-entrypoint.sh development'
        ;;
    "laravel-scheduler")
        exec /bin/sh -c '/sbin/tini -- /usr/bin/supervisord -n -c /etc/supervisord-scheduler.conf'
        ;;
    "laravel-worker")
        exec /bin/sh -c '/sbin/tini -- /usr/bin/supervisord -n -c /etc/supervisord-worker.conf'
        ;;
    "migrate")
        exec /sbin/tini -- /usr/local/bin/php /var/www/app/artisan migrate
        ;;
    "/bin/sh" | "sh" | "/bin/bash" | "bash" )
        exec "$@"
        ;;
    *)
        echo "Usage: ${0} {webserver|laravel|laravel-scheduler|laravel-worker|bash|sh}" >&2
        exit 3
        ;;
esac
