<?php
class Connection {

    /**
     * Connection constructor.
     */
    private function __construct() {}

    private static ?PDO $connection = null;
    private static string $string_connection = "mysql:host=localhost;dbname=practica_04_pow";
    private static string $user = 'root', $password = "derian2020";

    /**
     * @return PDO|null
     */
    public static function connect_database(): ?PDO {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        if(!isset(self::$connection))
            self::$connection = new PDO(self::$string_connection, self::$user, self::$password, $pdo_options);
        return self::$connection;
    }


}