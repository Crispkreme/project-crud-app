## Installation

Terminal: `Ubuntu`  
Command:  
```sh
composer create-project laravel/laravel project-app
```

**change the .env file**
find the **DB_CONNECTION**

from

Terminal: `Ubuntu`  
Command:  
```sh
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

to

Terminal: `Ubuntu`  
Command:  
```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_crud_app
DB_USERNAME=root
DB_PASSWORD=root
```

## Create a database using cmd or using Xampp GUI

Terminal: `Ubuntu`  
Command:  
```sh
sudo mysql -u root -p               # open mysql database
SHOW DATABASES;                     # display databases
CREATE DATABASE your_database_name; # create your databases
```
## migrate your database

Terminal: `Ubuntu`  
Command:  
```sh
php artisan migrate
```

## create a migration files

Terminal: `Ubuntu`  
Command:  
```sh
php artisan make:model -m
```
