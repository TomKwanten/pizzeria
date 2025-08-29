<?php
// initSession.php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialiseer winkelmandje als het nog niet bestaat
if (!isset($_SESSION['winkelmandje'])) {
    $_SESSION['winkelmandje'] = [];
}
