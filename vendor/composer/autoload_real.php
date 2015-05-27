<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInite628e2549f16982c625ccb6e6ed598b5
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }

        $directoryLevel = substr_count($_SERVER['PHP_SELF'], '/') - 1;

        if($class== 'Db'){
            $classLocation = 'functions/'.strtolower($class).'.php';
        } else {
            $classLocation = 'models/'.strtolower($class).'.php';
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

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInite628e2549f16982c625ccb6e6ed598b5', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInite628e2549f16982c625ccb6e6ed598b5', 'loadClassLoader'));

        $map = require __DIR__ . '/autoload_namespaces.php';
        foreach ($map as $namespace => $path) {
            $loader->set($namespace, $path);
        }

        $map = require __DIR__ . '/autoload_psr4.php';
        foreach ($map as $namespace => $path) {
            $loader->setPsr4($namespace, $path);
        }

        $classMap = require __DIR__ . '/autoload_classmap.php';
        if ($classMap) {
            $loader->addClassMap($classMap);
        }

        $loader->register(true);

        return $loader;
    }
}

function composerRequiree628e2549f16982c625ccb6e6ed598b5($file)
{
    require $file;
}
