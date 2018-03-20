#!/bin/bash

bin/console doc:database:drop --force
bin/console doc:database:create
bin/console doc:migrations:migrate --no-interaction
rm -rf web/images/items/
mkdir -p web/images/items/
bin/console hautelook:fixtures:load --no-interaction
