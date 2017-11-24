#!/bin/bash

if [[ ! -f .env ]]; then
    cat .env.dist | sed "s/LOCAL_USER_ID=1000/LOCAL_USER_ID=$(id -u)/" | sed "s/LOCAL_GROUP_ID=1000/LOCAL_GROUP_ID=$(id -g)/" > .env
fi
docker-compose up -d
docker-compose exec fpm composer self-update
docker-compose exec fpm composer install --prefer-dist -n
docker-compose run npm npm install
docker-compose exec fpm bin/console doc:database:drop --force
docker-compose exec fpm bin/console doc:database:create
docker-compose exec fpm bin/console doc:schema:update --force
echo -e "Generating project fixtures. It may take some time..."
if [ -d web/images/items ]; then
    docker-compose exec fpm rm -rf web/images/items/
fi
docker-compose exec fpm mkdir -p web/images/items/
docker-compose exec fpm bin/console hautelook:fixtures:load --no-interaction
echo -e "\n\e[30;48;5;82m                                                   \e[0m"
echo -e "\e[30;48;5;82m  [SUCCESS] DINDER Project is READY to launch! ;)  \e[0m"
echo -e "\e[30;48;5;82m                                                   \e[0m\n"
