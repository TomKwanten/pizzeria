<?php
// initTwig.php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

// Twig loader en environment
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/Presentation');
$twig = new \Twig\Environment($loader, [
    'cache' => false,   // Zet op een folder bij productie voor caching
    'debug' => false,   // Debug uit in productie
]);
