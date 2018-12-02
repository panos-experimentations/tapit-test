<?php

// set config and autoload

$configFileName = 'config.php';
if (file_exists($configFileName)) {
    $config = require $configFileName;
} else {
    die("missing `$configFileName`");
}

$autoloadFileName = './vendor/autoload.php';
require $autoloadFileName;

try {
    $app = new \TapItTest\App($config);

    // run the server app loop
    $app->runServer();

} catch (\Exception $exception) {
    echo "Error:\n\n";
    echo $exception->getMessage();
    echo "\n\n";
}
