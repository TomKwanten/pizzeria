<?php 
// init.php
declare(strict_types=1);

// Autoloader voor je project classes
spl_autoload_register(function ($class) {
    $paths = ['Entities', 'Business', 'Data']; // mappen waar je classes staan
    foreach ($paths as $path) {
        $file = __DIR__ . '/' . $path . '/' . basename(str_replace('\\', '/', $class)) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
