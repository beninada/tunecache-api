# TuneCache API

## Installation

Install [Laradock](https://laradock.io) as a submodule
```
git submodule update --init
```

Set up Laradock
```
cd laradock-tunecache
cp env-example .env
```

Bring up the server and DB containers
```
docker-compose up apache2 postgres
```

Install the API
```
# Enter the workspace container
docker exec -it tunecache_workspace_1 bash

# Execute the following in the container
cp .env.example .env
composer install
php artisan migrate
php artisan db:seed
php artisan passport:install
```

## Running

To run API at 127.0.0.1:80 and DB at 127.0.0.1:5432
```
docker-compose up nginx postgres
```

To access API working directory in container (to run artisan commands, migrations, etc.)
```
docker exec -it tunecache_workspace_1 bash
```

To access DB, download [PSequel](http://www.psequel.com/) or your favorite DB tool and log in with DB credentials in .env
