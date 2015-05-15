<?php

    define('SQLSERVER_NAME', 'localhost');
    define('SQLSERVER_USERNAME', 'root');
    define('SQLSERVER_PASSWORD', 'root');
    define('SQLSERVER_DATABASE_NAME', 'vineyardCompassion');

    function __autoload($class_name) {
        $directoryLevel = substr_count($_SERVER['PHP_SELF'], '/') - 1;

        if(strpos('DO_', $class_name) !== false) {
            $classLocation = 'functions/dal/' . $class_name . '.php';
        } else {
            $classLocation = 'functions/' . $class_name . '.php';
        }

        while($directoryLevel > 0) {
            $classLocation = '../' . $classLocation;
            $directoryLevel--;
        }

        require $classLocation;
    }