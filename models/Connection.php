<?php
class Connection {

    /**
     * Connection constructor.
     */
    private function __construct() {}

    private static ?PDO $connection = null;

    /**
     * @return PDO|null
     */
    public static function connect_database(): ?PDO {
        include_once 'configuration/Configuration.php';
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        try {
            if(self::$connection === null)
                self::$connection = new PDO("mysql:host=".Configuration::get_host().";dbname=".Configuration::get_database(), Configuration::get_user(), Configuration::get_password(), $pdo_options);
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
        return self::$connection;
    }


}