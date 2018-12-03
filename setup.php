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

    // try to do the app set
    setup($app);
} catch (\Exception $exception) {
    echo "Error:\n\n";
    echo $exception->getMessage();
    echo "\n\n";
}

function setup(\TapItTest\App $app)
{
    echo "\n\nsetup:\n\n";
    // check if config is there
    if (!file_exists('config.php')) {
        echo "error: no config.php\n\n";
        return;
    }

    // set up db
    /** @var PDO $db */
    $db = \TapItTest\App::db()->getDbh();

    $sqls = [];

    // drop tables

    if (in_array('-r', $_SERVER['argv'])) {
        echo "About to drop tables, are you sure you want to do this?  Type 'yes' to continue: ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        $line = strtolower(trim($line));
        if($line != 'yes' && $line != 'y'){
            echo "\nABORTING!\n";
            exit;
        }

        $sqls[] = "drop table if exists devices;";
    }

    // create tables

    $sqls[] = <<<SQL
    create table devices (
      id varchar(255) primary key,
      name varchar(255)
    );
SQL;

    $sqls[] = <<<SQL
    create table locations (
      id serial primary key,
      device_id varchar(255) references devices(id),
      LatLong varchar(255),
      created_at timestamp
    );
SQL;

    foreach ($sqls as $sql) {
        try {
            $db->exec($sql);
        } catch (Exception $exception) {
            echo "\n$sql";
            echo "\n{$exception->getMessage()}";
            echo "\n";
        }
    }

}
