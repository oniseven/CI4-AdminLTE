# CodeIgniter 4 (4.6.0) + AdminLTE (3.2.0)

## Server Requirements (default codeigniter 4 requirements)

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

## Installation
create database using spark or other tools

```cli
php spark db:create your_database
```

run database setup command, 
this part will run migration and seeders
to create the tables and adding some dummy data

```cli
php spark setup:datatabse
```
