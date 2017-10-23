#!/bin/bash

docker-compose up -d
vendor/bin/phpunit
docker-compose stop