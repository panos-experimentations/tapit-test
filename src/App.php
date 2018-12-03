<?php

namespace TapItTest;
/**
 * define all methods in the container
 * @method static View view() return View
 * @method static Db db() return View
 *
 */
class App
{
    public static $container = [];

    /**
     * App constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct($config = [])
    {
        self::$container = []; // clear container
        self::$container['config'] = $config;
        // @TODO move this $_SERVER check to somewhere else?
        self::$container['SERVER_REQUEST_URI'] = $_SERVER['REQUEST_URI'] ?? '';
        self::$container['SERVER_REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'] ?? '';

        if (!empty($config)) {
            // init db
            self::$container['db'] = new Db(
                self::$container['config']['DB_USERNAME'],
                self::$container['config']['DB_PASSWORD'],
                self::$container['config']['DB_NAME'],
                self::$container['config']['DB_DRIVER']
            );
        }
    }

    /**
     * This will allow us to use something like App::view()->render()
     * (if we have View obj as `view` set in the container)
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        if (isset(self::$container[$name])) {
            return self::$container[$name];
        }

        throw new \Exception("`$name` not set in App::container");
    }

    /**
     * @throws \Exception
     */
    public function runWeb(): void
    {
        $action = Util::uriParams(self::$container['SERVER_REQUEST_URI'])['action'];

        // check credentials
        if (!Util::hasCredentials($action)) {
            // redirect to login
            $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
            Util::redirectToLogin();
        }

        // execute action
        Util::executeAction($action);
    }

    public function runServer(): void
    {
        /* Allow the script to hang around waiting for connections. */
        set_time_limit(0);

        /* Turn on implicit output flushing so we see what we're getting
         * as it comes in. */
        ob_implicit_flush();

        $address = self::$container['config']['SERVER_IP'];
        $port = self::$container['config']['SERVER_PORT'];

        if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        }

        if (socket_bind($sock, $address, $port) === false) {
            echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
        }

        if (socket_listen($sock, 5) === false) {
            echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
        }

        do {
            if (($msgsock = socket_accept($sock)) === false) {
                echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
                break;
            }

            if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
                echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
                break;
            }

            // this will save location or set device name
            Service::payload($buf);

            //$talkback = "ok\n";
            //socket_write($msgsock, $talkback, strlen($talkback));
            //echo "$buf\n";
            socket_close($msgsock);
        } while (true);

        socket_close($sock);
    }

}