---
layout: default
---

# Docker

A [Dockerfile](../Dockerfile) included in this project allows you to run example script in html page or cli console and run unit tests.

## Initialization

### Build container
First you need to build the container.
```bash
docker build -t quotable-php .
```
You must launch **again** this command each time you make changes in Dockerfile.

### Fetch libraries with composer
And you need to fetch all needed libraries describe in [composer.json](../composer.json) file.
```bash
docker run -it --rm --name quotable-php -v "$(pwd)":/application quotable-php composer install
```
You must launch **again** this command each time you make changes in composer.json file.

## Run unit test
```bash
docker run -it --rm --name quotable-php -v "$(pwd)":/application quotable-php composer run-script test
```

# Run example page

The [example script](../example/index.php) will use Google Api Key found in file `.env` located in `example` directory.

Copy example file [`.env.dist`](../example/.env.dist) as `.env` in the same directory, edit it (don't forget to use your own Google Api Key) and save it.

## Display example in your browser
```bash
docker run -it --rm --name quotable-php -v "$(pwd)":/application -p 8080:80 quotable-php
```
Open your browser and go to http://localhost:8080/

## Display example in your console
```bash
docker run -it --rm --name quotable-php -v "$(pwd)":/application quotable-php php example/index.php
```
