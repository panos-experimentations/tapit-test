<?php

namespace TapItTest;

use TapItTest\Models\Device;
use TapItTest\Models\Location;

/**
 * Class Controller have actions methods that should return one of:
 * - [string] view name (like `index` for src/views/index.php)
 * - [array] with view name and array with data to pass to a view
 * - [View object] View object with already set view name and data
 *
 * @package TapItTest
 */
class Controller
{
    function index()
    {
        return 'index';
    }

    function foo()
    {
        $d = new Device();
        $d->create('foo');
        return ['test', ['msg' => 'data FOO', 'values' => ['a', 'b']]];
    }

    /**
     * @return string
     * @throws \Exception
     */
    function login()
    {
        // just display login form if it is not POST
        if (App::$container['SERVER_REQUEST_METHOD'] !== 'POST') {
            return 'login';
        }

        if (Util::checkLogin(App::$container['config'], $_POST)) {
            // @TODO move `mark login auth` somewhere else
            $_SESSION['auth'] = true;
            if (isset($_SESSION['return_to'])) {
                $returnTo = $_SESSION['return_to'];
                unset($_SESSION['return_to']);
                Util::redirectTo($returnTo);
            }
        }

        return 'login';
    }

    function logout()
    {
        session_destroy();
        Util::redirectToLogin();
    }


}