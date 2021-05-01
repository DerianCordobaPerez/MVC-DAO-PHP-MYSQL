<?php
class Form {
    private function __construct() {}

    /**
     * Inicio del componente form
     * @param $file
     * @param $method
     * @param $enctype
     * @return void
     */
    public static function open_form(string $file, string $method, string $enctype = 'multipart/form-data'): void {
        echo "<form method='$method' ";
        if($enctype === 'multipart/form-data') echo "enctype='$enctype'";
        if($file !== 'no-file') echo " action='$file'";
        echo ">";
    }

    /**
     * Fin del componente form
     * @param null
     * @return void
     */
    public static function close_form(): void {
        echo "</form>";
    }
}