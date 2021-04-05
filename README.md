# LEARNING SYMFONY

How to run?

- git clone this repository and copy file **.env.example** to **.env**
- run **composer install** inside project
- edit file **.env** and change config database
- see file routes.yaml **src/Resources** for available routes in this project
- run **php bin/console lexik:jwt:generate-keypair** for generate private key jwt
- run **php bin/console doctrine:database:create** for create database automatically
- run **php bin/console doctrine:migrations:migrate** for make table automatically
- run **php bin/console doctrine:fixture:load** for seeders book
- run **php -S localhost:8000 -t public/** for running this project


