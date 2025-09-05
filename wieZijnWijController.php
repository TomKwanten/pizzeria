<?php
// wieZijnWijController.php
declare(strict_types=1);

require_once("init.php");
require_once("initTwig.php");
require_once("initSession.php");

// Render de “Wie zijn wij”-pagina
print $twig->render("wieZijnWij.twig", [
    'ingelogd' => $_SESSION['klant'] ?? false,
    'winkelmandjeAantal' => count($_SESSION['winkelmandje'] ?? [])
]);
