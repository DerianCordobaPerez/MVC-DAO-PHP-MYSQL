<?php
class Configuration {
    private const DB_HOST = 'localhost';
    private const DB_DATABASE = 'practica_04_pow';
    private const DB_USER = 'root';
    private const DB_PASSWORD = 'DerianUnanLeonProgramador#14102021';

    public static function get_host(): string {return self::DB_HOST;}

    public static function get_database(): string {return self::DB_DATABASE;}

    public static function get_user(): string {return self::DB_USER;}

    public static function get_password(): string {return self::DB_PASSWORD;}

}