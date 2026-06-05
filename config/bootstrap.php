<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);


    $baseDir = __DIR__ . '/../';

    if (str_starts_with($class, 'config\\')) {
        $file = $baseDir . $classPath . '.php';
    } else {
        $file = $baseDir . 'src/' . $classPath . '.php';
    }

    if (file_exists($file)) {
        require_once $file;
    }
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}