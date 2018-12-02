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

        // init db
        self::$container['db'] = new Db(
            self::$container['config']['DB_USERNAME'],
            self::$container['config']['DB_PASSWORD'],
            self::$container['config']['DB_NAME'],
            self::$container['config']['DB_DRIVER']
        );
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

        echo "\nall ok\n";
        // @TODO run feed loop
//        do {
//
//            // server stuff
//            // read TCP feed
//
//            echo "TODO: implement TCP feed server\n";
//            sleep(5);
//
//        } while (true);

    }

}