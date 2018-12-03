# tapit-test
TapIT – Audition Task for PHP Developer

## Setup

- create database to be used for this project [tested with PostgreSQL]
- copy `config.sample.php` to `config.php` and set appropriate values
- run `composer install` [this will just generate autoload.php, there is no dependencies]
- run `php setup.php` [this will needed db tables]
- start TCP server with `php server.php` [ip and port in config.php]
- point your web server to `__path__to__the__project__/public/index.php`
- point your web browser to the project and login with credentials set in `config.php` [username/password are hardcoded in config]


## TODO (front end) 
 
- add some map (google maps api?)
- populate with points from backend ('\devices_last_locations' ?)
- ajax refresh
- some css

## TODO (back end)

- TCP is one line per connection so `a device may send multiple lines (e.g. multiple locations) in a single connection;` from specification will not work (how to end connection?)

## Note

- PHP probably is not the best solution for a TCP server, not sure how it will work with multiple devices if some device take a long time to send data packet
- should get better documentation for TCP data-packet and $GPRMC