#!/bin/bash
docker stop db
docker stop app
docker stop webserver
docker stop phpmyadmin
docker container prune
docker rmi -f $(docker images -aq)