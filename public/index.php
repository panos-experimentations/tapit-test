<?php

// set config and autoload

$configFileName = '../config.php';
if (file_exists($configFileName)) {
    $config = require $configFileName;
} else {
    die("missing `$configFileName`");
}

$autoloadFileName = '../vendor/autoload.php';
require $autoloadFileName;

try {

    // run the web app and display content
    $app = new \TapItTest\App($config);

    // check auth
    \TapItTest\App::$container['auth'] = \TapItTest\Util::checkAuth();

    // run app
    $app->runWeb();

    // display content
    echo \TapItTest\App::view()->render();

} catch (\Exception $exception) {
    echo "Error:\n\n";
    echo $exception->getMessage();
    echo "\n\n";
}
