#!/bin/bash
rm -rf web/images/items/
mkdir web/images/items/
docker-compose exec fpm bash -c "bin/console doc:schema:drop --force && bin/console doc:schema:create && bin/console hautelook:fixtures:load"