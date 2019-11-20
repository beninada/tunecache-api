# TuneCache API

## Installation

Set up Laradock
```
cd laradock
cp env-example .env
docker-compose up apache2 postgres
```

Install the API
```
# Enter the workspace container
docker exec -it tunecache_workspace_1 /bin/bash

# Then execute the following in the container
cp env-example .env
composer install
php artisan migrate
php artisan db:seed
php artisan passport:install
```

Add the following to your hosts file (usually located at /etc/hosts) to make the API accessible at http://tunecache.test
```
127.0.0.1   tunecache.test
```

## Running

To run the API
```
docker-compose up apache2 postgres
```

To access the API's working directory in container (for running artisan commands, migrations, etc.)
```
docker exec -it tunecache_workspace_1 /bin/bash
```

To access the DB, download [PSequel](http://www.psequel.com/) or your favorite DB tool and log in with DB credentials in .env

## Notes

Some things may not work before you have all of the secrets loaded into your .env file. Obtain them from the lead developer.
