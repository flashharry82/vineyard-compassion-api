<?php

    define('SQLSERVER_NAME', 'localhost');
    define('SQLSERVER_USERNAME', 'root');
    define('SQLSERVER_PASSWORD', 'root');
    define('SQLSERVER_DATABASE_NAME', 'vineyardCompassion');

    function my_autoloader($class_name) {
        $directoryLevel = substr_count($_SERVER['PHP_SELF'], '/') - 1;

        if($class_name == 'Db'){
            $classLocation = 'functions/'.from_camel_case($class_name).'.php';
        } else {
            $classLocation = 'models/'.from_camel_case($class_name).'.php';
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

    function from_camel_case($input) {
      preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
      $ret = $matches[0];
      foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
      }
      return implode('_', $ret);
    }

    spl_autoload_register('my_autoloader');

?>