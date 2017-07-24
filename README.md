FAQ
===

A Symfony3 project created on July 22, 2017, 3:36 pm wich php5, this project was created to manage the content of FAQ and to receive questions.

# Requirements

PHP 5.5.9 or higher

# Installation

If you don't have Composer installed in your computer, start by installing [Composer globally](https://getcomposer.org/download/). run this command to install the dependencies

```
composer install
```

Checking Requirements, if has something wrong this command will help.

```
php bin/symfony_requirements
```

# Configuration

insert your database configuration on this file "app/config/parameters.yml"

```
parameters:
    database_host: 127.0.0.1
    database_port: null
    database_name: faq
    database_user: root
    database_password: null
```

The email was send by mailgun, insert sender configuration on "app/config/config.yml"

```
parameters:
    mailgun_key: key-a32b67e699694dff907009c15e9d634a
    mailgun_domain: sandbox02aa3cdd7e5f4ac8b7777bca92e1bb55.mailgun.org
    mailgun_to: lirio_kuhnen@hotmail.com
```

# DataBase

create database

```
php bin/console doctrine:database:create
```

Create schema to database

```
php bin/console doctrine:schema:update --force
```

# Create admin user

Run this command to load the fixtures and create the admin user. username admin and password admin

```
php bin/console doctrine:fixtures:load
```

# Run the project

To run the project

```
php bin/console server:run
```

You can access the FAQ on http://127.0.0.1:8000 and to admin use http://127.0.0.1:8000/admin





