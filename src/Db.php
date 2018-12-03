<?php

namespace TapItTest;

/**
 * Class Db, for now, just a container for dbh
 * @package TapItTest
 */
class Db
{
    private $dbh;
    /**
     * Db constructor.
     * @param $username
     * @param $password
     * @param $db_name
     * @param string $host
     * @throws \Exception
     */
    public function __construct($username, $password, $db_name, $db_driver = 'pgsql', $host = '127.0.0.1')
    {
        $dsn = "{$db_driver}:dbname={$db_name};host={$host}";

        try {
            $dbh = new \PDO($dsn, $username, $password);
        } catch (\Exception $exception) {
            throw new \Exception('Error connecting to the database!');
        }

        $dbh->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );//Error Handling

        $this->dbh = $dbh;
    }

    /**
     * @return \PDO
     */
    public function getDbh()
    {
        return $this->dbh;
    }
}