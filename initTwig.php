<?php
// initTwig.php
declare(strict_types=1);

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/Presentation');
$twig = new \Twig\Environment($loader);
