<?php

if (!isset($_SERVER['BUZZ_DIR'])) {
    throw new RuntimeException('You must specify a BUZZ_DIR environment variable.');
}

spl_autoload_register(function($class)
{
    if (0 === strpos($class, 'Buzz\\Extension\\')) {
        $dir = __DIR__.'/../lib';
    } elseif (0 === strpos($class, 'Buzz\\')) {
        $dir = $_SERVER['BUZZ_DIR'];
    } else {
        return false;
    }

    if (file_exists($file = $dir.'/'.str_replace('\\', '/', $class).'.php')) {
        require_once $file;
        return true;
    }
});
