SPA CRUD-PROJECT
================

An example of my first step in Symfony framework  

#### Back-end
 Tested on:
 - MariaDB 5.5
 - PHP 7.3.x
 - [Symfony 3](https://symfony.com/)
    - [Symfony Translation Bundle](http://php-translation.readthedocs.io/en/latest/symfony/index.html) ([github](https://github.com/php-translation/symfony-bundle))
    - [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)
    
#### Front-end
 - [VueJS 2](https://ru.vuejs.org/index.html)
    - [VeeValidate](http://vee-validate.logaretm.com/) - validator
    - [UIV](https://uiv.wxsm.space/) - UI components
 - Bootstrap 3

Installation
-------------

Move into the root of your project directory and run:

`composer install`

Set the values of parameters e.g.:

```php
database_host: 127.0.0.1
database_port: 3306
database_name: sf3_order_db
database_user: root
database_password: root
mailer_transport: smtp
mailer_host: 127.0.0.1
mailer_user: postmaster@localhost.ru
mailer_password: null
secret: 6960e13aedae1234b65a38000a2c0736bc0621f7
```

The file `sf3_order_db.sql` is a MySQL dump file (InnoDB engine)

Run server:
```
php bin/console server:run
```

And go to: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### FAQ

**How to create a new user account?**

`php bin/console fos:user:create`

**How to add an administrator role to a user account?**

`php bin/console fos:user:promote <account_name> ROLE_ADMIN`