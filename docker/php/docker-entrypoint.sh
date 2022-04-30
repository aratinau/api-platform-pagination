#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [[ "$1" = "supervisord" ]]; then

	echo "Waiting for db to be ready..."
	until bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
		sleep 1
	done

	#if ls -A src/Migrations/*.php > /dev/null 2>&1; then
	#	bin/console doctrine:migrations:migrate --no-interaction
	#fi
	# TODO REMOVE THIS (use migrations)
	bin/console doctrine:schema:update -n --force
fi

exec docker-php-entrypoint "$@"
