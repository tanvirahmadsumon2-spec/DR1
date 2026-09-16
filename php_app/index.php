<?php
declare(strict_types=1);

// Autoload function for Daktar Serial application classes
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Load configuration
$config = require_once __DIR__ . '/config/config.php';

// Helper for translation
require_once __DIR__ . '/app/Core/helpers.php';

// Initialize session
App\Core\Session::start();

// Dispatch router
$router = new App\Core\Router();
require_once __DIR__ . '/routes/web.php';

$router->dispatch();
