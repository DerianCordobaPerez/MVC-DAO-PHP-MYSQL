<?php

use JetBrains\PhpStorm\NoReturn;

class IndexController {

    public function __construct() {}

    #[NoReturn] public function init(): void {
        $module = "";
        if(isset($_REQUEST['module']))  $module = $_REQUEST['module'];

        if($module === 'student') {
            include_once 'StudentController.php';
            StudentController::init();
        } else if($module === 'car') {
            include_once 'CarController.php';
            CarController::init();
        }
        redirect();
    }
}