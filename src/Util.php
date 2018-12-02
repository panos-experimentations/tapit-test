<?php

namespace TapItTest;


class Util
{
    static function checkAuth(): bool
    {
        if (session_status() == PHP_SESSION_DISABLED) {
            throw new \Exception("Session not available");
        }
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['auth']) && $_SESSION['auth'] == true) {
            return true;
        }

        return false;
    }

    /**
     * @param $SERVER_REQUEST_URI string as foo/bar/1
     * @return array
     */
    static function uriParams($SERVER_REQUEST_URI): array
    {
        // default params
        $params = [
            'uri' => '',
            'action' => 'index',
            'args' => [],
        ];

        $params['uri'] = \trim($SERVER_REQUEST_URI, '/');
        $params['args'] = \explode('/', $params['uri']);
        if (isset($params['args'][0]) && $params['args'][0]) {
            $params['action'] = $params['args'][0];
        }

        return $params;
    }

    static function hasCredentials($action): bool
    {
        // all have credentials for /login action
        if ($action == 'login') {
            return true;
        }

        // for any other action, it is enough just to be logged in
        return App::$container['auth'] ?? false;
    }

    /**
     * @param $action
     * @throws \Exception
     */
    static function executeAction($action): void
    {
        $controller = new Controller();
        if (method_exists($controller, $action)) {
            App::$container['view'] = new View();
            $actionResponse = $controller->{$action}();
            if (is_null($actionResponse)) {
                return;
            }
            if (is_string($actionResponse)) {
                App::view()->view = $actionResponse;
                return;
            }
            if ($actionResponse instanceof View) {
                App::$container['view'] = $actionResponse;
                return;
            }
            if (is_array($actionResponse) && isset($actionResponse[1])) {
                App::view()->view = $actionResponse[0];
                App::view()->data = $actionResponse[1];
                return;
            }
            throw new \Exception("invalid action return data");
        }

        throw new \Exception("unknown action `$action`");
    }

    /**
     * @param $config
     * @param $post
     * @return bool
     * @throws \Exception
     */
    public static function checkLogin($config, $post): bool
    {
        if (!isset($config['AUTH_USERNAME']) || !isset($config['AUTH_PASSWORD'])) {
            throw new \Exception('Missing `config` AUTH_USERNAME or AUTH_PASSWORD');
        }
        if (!isset($post['username']) || !isset($post['password'])) {
            throw new \Exception('Missing `POST` user or password');
        }
        if (($post['username'] !== $config['AUTH_USERNAME']) || ($post['password'] !== $config['AUTH_PASSWORD'])) {
            throw new \Exception('Wrong username and/or password');
        }
        return true;
    }

    public static function redirectToLogin()
    {
        Util::redirectTo('/login');
    }
    public static function redirectTo($location)
    {
        header("Location: $location");
        exit;
    }

}