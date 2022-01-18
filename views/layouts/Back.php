<?php
include_once 'components/Button.php';
include_once 'components/Divs.php';
class Back {

    public function __construct() {}

    public static function back(): void {
        echo "<script src='public/back_home.js'></script>";
    }
}