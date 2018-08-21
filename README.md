
videotech
=========

PHP version : 7.0.30

Bundles used :
 - friendsofsymfony/jsrouting-bundle : automatique Routing url in imported javascript
 - friendsofsymfony/user-bundle: User management
 - knplabs/knp-paginator-bundle: Auto pagination
 - symfony/assetic-bundle: Assetic management
 - vich/uploader-bundle: Manager photo uploads

HTML/JS frameworks :
 - Bootstrap 4
 - JQuery

Database :
 - MariaDB (MySQL)

Web Browser tested :
 - Firefox

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
You can also configurate an Apache/ngix or other webserver supporting php.

