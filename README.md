# tapit-test
TapIT – Audition Task for PHP Developer

## Setup

- create database to be used for this project [tested with PostgreSQL]
- copy `config.sample.php` to `config.php` and set appropriate values
- run `composer install` [this will just generate autoload.php, there is no dependencies]
- run `php setup.php` [this will needed db tables]
- start TCP server with `php server.php`
- point your web server to `__path__to__the__project__/public/index.php`
- point your web browser to the project and login with credentials set in `config.php` [username/password are hardcoded in config]
