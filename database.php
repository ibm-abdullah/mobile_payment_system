<?php

/**
 * Class to create and issue database connections
 * This would force the system to use an available database instance or create a new one
 * */
class Database {

    private static $dbh = null;
    private $conn = null;

    private function __construct() {

        //$this->conn = new
        $this->conn = new PDO("mysql:host=localhost;port=3306;dbname=ibrahimabdullah", "root", "Ashesi@123456789");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Method to get a database instance or create one if none is to be found
     * @param void
     * @return database connection instance
     */
    public static function getInstance() {
        if (null === self::$dbh) {
            self::$dbh = (new Database())->conn;
        }
        return self::$dbh;
    }

}
