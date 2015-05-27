<?php

    define('SQLSERVER_NAME', 'localhost');
    define('SQLSERVER_USERNAME', 'root');
    define('SQLSERVER_PASSWORD', 'root');
    define('SQLSERVER_DATABASE_NAME', 'vineyardCompassion');

    function my_autoloader($class_name) {
        $directoryLevel = substr_count($_SERVER['PHP_SELF'], '/') - 1;

        if($class_name == 'Db'){
            $classLocation = 'functions/'.$class_name.'.php';
        } else {
            $classLocation = 'models/'.strtolower($class_name).'.php';
        }
        

        while($directoryLevel > 0) {
            $classLocation = '../' . $classLocation;
            $directoryLevel--;
        }

        if ( ! file_exists($classLocation))
        {
            return FALSE;
        }

        require $classLocation;
    }

    spl_autoload_register('my_autoloader');

?>