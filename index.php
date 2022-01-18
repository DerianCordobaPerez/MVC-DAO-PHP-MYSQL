<?php
    include_once 'views/IndexView.php';
    include_once 'controllers/IndexController.php';
    (new IndexView())->show_index('Practica #04');
    (new IndexController)->init();