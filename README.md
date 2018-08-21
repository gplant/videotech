
videotech
=========

A Symfony project created on August 20, 2018, 7:35 am.
=======
# videotech

PHP version : 7.0.30

=======

Setup :
Configure database and mailer information in app/config/parameters.yml

Install dependancies :
php composer update

Create database :
bin/console doctrine:schema:create

Create admin user :
bin/console fos:user:creat admin --super-admin

Launche server :
bin/console server:run

# You can also configurate an Apache/ngix or other webserver supporting php.

